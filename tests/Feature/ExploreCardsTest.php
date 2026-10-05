<?php

namespace Tests\Feature;

use App\Models\CategoryPackage;
use App\Models\Package;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Pengaturan > Kelola Website > Kartu Explore Village. */
class ExploreCardsTest extends TestCase
{
    private function admin(): User
    {
        return User::where('role_id', 1)->firstOrFail();
    }

    public function test_admin_manages_cards_and_homepage_shows_them(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $name = 'Kartu Uji '.uniqid();

        $this->actingAs($admin)->get(route('explore-cards.index'))->assertOk()->assertSee('Culture');
        $this->actingAs($admin)->get(route('explore-cards.create'))->assertOk();

        // Gambar wajib saat tambah.
        $this->actingAs($admin)->post(route('explore-cards.store'), ['name' => $name])->assertSessionHasErrors(['image']);

        $this->actingAs($admin)->post(route('explore-cards.store'), [
            'name' => $name, 'name_id' => 'Kartu Uji ID', 'desc' => 'English desc', 'desc_id' => 'Deskripsi ID',
            'url' => 'tour-packages', 'sort_order' => 0, 'status' => 1,
            'image' => UploadedFile::fake()->image('card.webp', 600, 800),
        ])->assertRedirect(route('explore-cards.index'));

        $card = Tag::where('name', $name)->firstOrFail();
        Storage::disk('public')->assertExists('tag/'.$card->image);

        try {
            $this->actingAs($admin)->get(route('explore-cards.edit', $card->id))->assertOk()->assertSee($name);

            $this->get('http://localhost/en')->assertSee($name)->assertSee('English desc')
                ->assertSee('href="http://localhost/en/tour-packages"', false);
            $this->get('http://localhost/id')->assertSee('Kartu Uji ID')->assertSee('Deskripsi ID')
                ->assertSee('href="http://localhost/id/tour-packages"', false);

            // Ganti gambar → file lama dihapus.
            $oldImage = $card->image;
            $this->actingAs($admin)->put(route('explore-cards.update', $card->id), [
                'name' => $name, 'sort_order' => 0, 'status' => 1, 'image' => UploadedFile::fake()->image('new.jpg'),
            ])->assertRedirect(route('explore-cards.index'));
            Storage::disk('public')->assertMissing('tag/'.$oldImage);

            // Disembunyikan → tidak tampil di beranda.
            $this->actingAs($admin)->post(route('explore-cards.toggle', $card->id));
            $this->assertFalse($card->fresh()->status);
            $this->get('http://localhost/en')->assertDontSee($name);
        } finally {
            Tag::where('id', $card->id)->delete();
        }
    }

    public function test_tag_used_by_package_cannot_be_deleted(): void
    {
        $package = Package::first();
        if (! $package) {
            $this->markTestSkipped('Tidak ada paket.');
        }
        $card = Tag::create(['name' => 'Dipakai '.uniqid(), 'desc' => '', 'image' => 'x.png', 'status' => false]);
        $link = CategoryPackage::create(['package_id' => $package->id, 'tag_id' => $card->id]);

        try {
            $this->actingAs($this->admin())->delete(route('explore-cards.destroy', $card->id))->assertSessionHas('error');
            $this->assertNotNull(Tag::find($card->id));
        } finally {
            $link->delete();
            $card->delete();
        }
    }

    public function test_only_super_admin(): void
    {
        $village = User::where('role_id', 2)->first();
        if (! $village) {
            $this->markTestSkipped('Tidak ada user admin desa.');
        }
        $this->actingAs($village)->get(route('explore-cards.index'))->assertForbidden();
    }
}
