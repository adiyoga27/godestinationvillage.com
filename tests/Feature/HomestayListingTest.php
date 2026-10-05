<?php

namespace Tests\Feature;

use App\Models\Homestay;
use App\Models\VillageDetail;
use Tests\TestCase;

/** Halaman /homestay: filter, urutan & harga diskon. */
class HomestayListingTest extends TestCase
{
    private array $ids = [];

    protected function tearDown(): void
    {
        Homestay::withTrashed()->whereIn('id', $this->ids)->forceDelete();
        parent::tearDown();
    }

    private function make(array $attrs): Homestay
    {
        $homestay = Homestay::create(array_merge([
            'name' => 'Uji '.uniqid(), 'description' => 'Deskripsi uji', 'location' => 'Lokasi uji', 'price' => 500000,
            'disc' => 0, 'is_breakfast' => 0, 'is_active' => 1, 'category_id' => 1, 'slug' => 'uji-'.uniqid(),
            'owner_name' => 'Tuan Uji', 'check_in_time' => '14.00', 'check_out_time' => '12.00',
        ], $attrs));
        $this->ids[] = $homestay->id;

        return $homestay;
    }

    private function names(string $query): array
    {
        $paginator = $this->get('http://localhost/en/homestay'.$query)->assertOk()->viewData('packages');

        return collect($paginator->items())->pluck('name')->all();
    }

    public function test_filters_by_search_village_type_price_and_breakfast(): void
    {
        $tag = 'Zq'.uniqid();
        $village = VillageDetail::first();
        $cheap = $this->make(['name' => "$tag Murah", 'price' => 250000, 'is_breakfast' => 1, 'village_id' => $village?->id]);
        $disc = $this->make(['name' => "$tag Diskon", 'price' => 900000, 'disc' => 450000, 'category_id' => 2]);
        $pricey = $this->make(['name' => "$tag Mahal", 'price' => 2000000]);
        $this->make(['name' => "$tag Nonaktif", 'is_active' => 0]);

        $this->assertEqualsCanonicalizing([$cheap->name, $disc->name, $pricey->name], $this->names("?q=$tag"));
        $this->assertSame([$cheap->name], $this->names("?q=$tag&breakfast=1"));
        $this->assertSame([$disc->name], $this->names("?q=$tag&type=2"));
        // Rentang harga memakai harga setelah diskon (disc = harga akhir).
        $this->assertSame([$disc->name], $this->names("?q=$tag&price=300k-700k"));
        $this->assertSame([$pricey->name], $this->names("?q=$tag&price=over-1500k"));
        $this->assertSame([$cheap->name, $disc->name, $pricey->name], $this->names("?q=$tag&sort=price_asc"));
        $this->assertSame([$pricey->name, $disc->name, $cheap->name], $this->names("?q=$tag&sort=price_desc"));
        if ($village) {
            $this->assertSame([$cheap->name], $this->names("?q=$tag&village={$village->id}"));
        }
        // Nilai tidak valid diabaikan.
        $this->assertCount(3, $this->names("?q=$tag&price=hack&sort=drop"));
    }

    public function test_card_shows_discounted_price_and_empty_state(): void
    {
        $tag = 'Zq'.uniqid();
        $this->make(['name' => "$tag Diskon", 'price' => 900000, 'disc' => 450000]);

        $this->get("http://localhost/id/homestay?q=$tag")
            ->assertSee('Rp 450.000')->assertSee('Rp 900.000')->assertSee('-50%')
            ->assertSee('1</strong> homestay ditemukan', false)
            ->assertSee('Reset semua');

        $this->get('http://localhost/id/homestay?q=tidak-ada-'.uniqid())
            ->assertSee('Tidak ada homestay yang cocok dengan filter Anda');
    }

    public function test_breadcrumb_home_not_duplicated(): void
    {
        $html = $this->get('http://localhost/id/homestay')->getContent();
        preg_match('/<ol[^>]*>(.*?)<\/ol>/s', $html, $m);

        $this->assertSame(1, substr_count($m[1] ?? '', 'Beranda'));
    }
}
