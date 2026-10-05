<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\LegalPage;
use App\Models\User;
use Tests\TestCase;

/** FAQ & Syarat dan Ketentuan dikelola dari admin. */
class FaqAndTermsTest extends TestCase
{
    private function admin(): User
    {
        return User::where('role_id', 1)->firstOrFail();
    }

    public function test_public_faq_shows_active_questions_with_faqpage_schema(): void
    {
        $category = FaqCategory::create(['title' => 'Test Cat '.uniqid(), 'title_id' => 'Kategori Uji', 'sort_order' => 999]);
        $shown = Faq::create(['faq_category_id' => $category->id, 'question' => 'Shown question?', 'question_id' => 'Pertanyaan tampil?', 'answer' => '<p>Shown <strong>answer</strong></p>']);
        $hidden = Faq::create(['faq_category_id' => $category->id, 'question' => 'Hidden question?', 'answer' => '<p>x</p>', 'is_active' => false]);

        try {
            $this->withSession(['locale' => 'en'])->get('/faq')
                ->assertOk()
                ->assertSee('Shown question?')
                ->assertSee('Shown <strong>answer</strong>', false)
                ->assertDontSee('Hidden question?')
                ->assertSee('"@type":"FAQPage"', false)
                ->assertSee('"text":"Shown answer"', false);

            // Bahasa Indonesia memakai versi ID, kosong → English.
            $this->withSession(['locale' => 'id'])->get('/faq')
                ->assertSee('Kategori Uji')
                ->assertSee('Pertanyaan tampil?')
                ->assertSee('Shown <strong>answer</strong>', false);
        } finally {
            $category->delete();
        }
    }

    public function test_admin_can_manage_faq(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get(route('faqs.index'))->assertOk()->assertSee('Tambah Pertanyaan');
        $this->actingAs($admin)->get(route('faq-categories.create'))->assertOk();

        $title = 'Kategori Admin '.uniqid();
        $this->actingAs($admin)->post(route('faq-categories.store'), ['title' => $title, 'sort_order' => 5, 'is_active' => 1])
            ->assertRedirect(route('faqs.index'));
        $category = FaqCategory::where('title', $title)->firstOrFail();

        try {
            $this->actingAs($admin)->get(route('faqs.create', ['category' => $category->id]))->assertOk();
            $this->actingAs($admin)->post(route('faqs.store'), [
                'faq_category_id' => $category->id, 'question' => 'Admin Q?', 'answer' => '<p>Admin A</p>', 'is_active' => 1,
            ])->assertRedirect(route('faqs.index'));
            $faq = Faq::where('faq_category_id', $category->id)->firstOrFail();

            $this->actingAs($admin)->get(route('faqs.edit', $faq->id))->assertOk()->assertSee('Admin Q?');
            $this->actingAs($admin)->put(route('faqs.update', $faq->id), [
                'faq_category_id' => $category->id, 'question' => 'Admin Q?', 'question_id' => 'Tanya admin?', 'answer' => '<p>Admin A</p>', 'is_active' => 0,
            ])->assertRedirect(route('faqs.index'));
            $this->assertSame('Tanya admin?', $faq->fresh()->question_id);
            $this->assertFalse($faq->fresh()->is_active);

            // Kategori berisi pertanyaan tidak bisa dihapus.
            $this->actingAs($admin)->delete(route('faq-categories.destroy', $category->id))->assertSessionHas('error');
            $this->actingAs($admin)->delete(route('faqs.destroy', $faq->id))->assertRedirect(route('faqs.index'));
            $this->actingAs($admin)->delete(route('faq-categories.destroy', $category->id))->assertRedirect(route('faqs.index'));
            $this->assertNull(FaqCategory::find($category->id));
        } finally {
            FaqCategory::where('title', $title)->delete();
        }
    }

    public function test_terms_page_editable_from_admin(): void
    {
        $page = LegalPage::where('key', 'terms')->firstOrFail();
        $original = $page->only(['title', 'title_id', 'content', 'content_id', 'last_updated']);

        $this->get('/term')->assertOk()->assertSee('id="privacy-policy"', false)->assertSee('1. Your Agreement');

        try {
            $admin = $this->admin();
            $this->actingAs($admin)->get(route('legal-pages.edit', 'terms'))->assertOk();
            $this->actingAs($admin)->put(route('legal-pages.update', 'terms'), [
                'title' => 'Terms & Conditions', 'title_id' => 'Syarat & Ketentuan',
                'content' => '<h2>Edited section</h2><p>Edited body</p>', 'content_id' => '<h2>Bagian diubah</h2>',
                'last_updated' => $page->last_updated->format('Y-m-d'),
            ])->assertRedirect(route('legal-pages.edit', 'terms'));

            // Isi berubah & tanggal tidak disentuh → tanggal jadi hari ini.
            $this->assertTrue($page->fresh()->last_updated->isToday());
            $this->withSession(['locale' => 'en'])->get('/term')->assertSee('Edited body');
            $this->withSession(['locale' => 'id'])->get('/term')->assertSee('Bagian diubah')->assertSee('Syarat &amp; Ketentuan', false);
        } finally {
            $page->fresh()->forceFill($original)->save();
        }
    }

    public function test_only_super_admin_can_open_settings(): void
    {
        $village = User::where('role_id', 2)->first();
        if (! $village) {
            $this->markTestSkipped('Tidak ada user admin desa.');
        }

        $this->actingAs($village)->get(route('faqs.index'))->assertForbidden();
        $this->actingAs($village)->get(route('legal-pages.edit', 'terms'))->assertForbidden();
        $this->actingAs($this->admin())->get(route('legal-pages.edit', 'unknown'))->assertNotFound();
    }
}
