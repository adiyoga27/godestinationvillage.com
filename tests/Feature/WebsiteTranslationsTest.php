<?php

namespace Tests\Feature;

use App\Models\TranslationOverride;
use App\Models\User;
use App\Support\DatabaseTranslationLoader;
use Tests\TestCase;

/** Pengaturan > Kelola Website > Bahasa Website. */
class WebsiteTranslationsTest extends TestCase
{
    private const KEY = 'Frequently Asked Questions';

    protected function tearDown(): void
    {
        TranslationOverride::where('key_hash', TranslationOverride::hashKey(self::KEY))->delete();
        DatabaseTranslationLoader::flush();
        parent::tearDown();
    }

    private function admin(): User
    {
        return User::where('role_id', 1)->firstOrFail();
    }

    public function test_admin_edits_text_and_website_uses_it(): void
    {
        $hash = TranslationOverride::hashKey(self::KEY);
        $this->actingAs($this->admin())->get(route('translations.index', ['q' => 'Frequently Asked']))
            ->assertOk()->assertSee('Pertanyaan yang Sering Diajukan');

        $this->actingAs($this->admin())->put(route('translations.update'), [
            'translations' => [$hash => ['id' => 'Tanya Jawab', 'en' => self::KEY]],
        ])->assertSessionHas('status', '1 teks berhasil disimpan');

        // Hanya bahasa yang berubah yang disimpan; file JSON tidak disentuh.
        $this->assertSame(['id'], TranslationOverride::where('key_hash', $hash)->pluck('locale')->all());
        $this->assertStringContainsString('"Pertanyaan yang Sering Diajukan"', file_get_contents(lang_path('id.json')));

        $this->get('http://localhost/id/faq')->assertSee('<title>Tanya Jawab | GODEVI</title>', false);
        $this->get('http://localhost/en/faq')->assertSee('<title>Frequently Asked Questions | GODEVI</title>', false);

        $this->actingAs($this->admin())->get(route('translations.index', ['filter' => 'changed']))->assertSee('Tanya Jawab');

        // Reset → kembali ke teks bawaan.
        $this->actingAs($this->admin())->delete(route('translations.reset', $hash))->assertSessionHas('status');
        $this->get('http://localhost/id/faq')->assertSee('<title>Pertanyaan yang Sering Diajukan | GODEVI</title>', false);
    }

    public function test_empty_or_default_value_removes_override_and_unknown_keys_ignored(): void
    {
        $hash = TranslationOverride::hashKey(self::KEY);
        TranslationOverride::create(['locale' => 'id', 'key_hash' => $hash, 'key' => self::KEY, 'value' => 'X']);

        $this->actingAs($this->admin())->put(route('translations.update'), [
            'translations' => [
                $hash => ['id' => ''],
                TranslationOverride::hashKey('kunci tidak ada') => ['id' => 'Y'],
            ],
        ]);

        $this->assertSame(0, TranslationOverride::where('key_hash', $hash)->count());
        $this->assertSame(0, TranslationOverride::where('value', 'Y')->count());
    }

    public function test_only_super_admin(): void
    {
        $village = User::where('role_id', 2)->first();
        if (! $village) {
            $this->markTestSkipped('Tidak ada user admin desa.');
        }

        $this->actingAs($village)->get(route('translations.index'))->assertForbidden();
    }
}
