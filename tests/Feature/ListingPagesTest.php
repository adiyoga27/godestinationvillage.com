<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Package;
use App\Models\VillageDetail;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Filter & urutan halaman /tour-packages, /village, /events, /news. */
class ListingPagesTest extends TestCase
{
    private array $packageIds = [];

    private array $eventIds = [];

    private array $postIds = [];

    protected function tearDown(): void
    {
        Package::withTrashed()->whereIn('id', $this->packageIds)->forceDelete();
        Event::whereIn('id', $this->eventIds)->delete();
        DB::table('post')->whereIn('id', $this->postIds)->delete();
        parent::tearDown();
    }

    private function names(string $url, string $field = 'name'): array
    {
        $paginator = collect($this->get('http://localhost'.$url)->assertOk()->original->getData())
            ->first(fn ($v) => $v instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator);

        return collect($paginator->items())->pluck($field)->all();
    }

    private function package(VillageDetail $village, array $attrs): Package
    {
        $package = Package::forceCreate(array_merge([
            'category_id' => 1, 'user_id' => $village->user_id, 'village_id' => $village->id, 'name' => 'x',
            'desc' => 'Deskripsi uji', 'price' => 500000, 'disc' => 0, 'is_active' => 1, 'slug' => 'uji-'.uniqid(),
        ], $attrs));
        $this->packageIds[] = $package->id;

        return $package;
    }

    public function test_tour_packages_filters(): void
    {
        $village = VillageDetail::whereHas('user', fn ($u) => $u->where('is_active', 1)->where('role_id', 2))->firstOrFail();
        $tag = 'Zp'.uniqid();
        $cheap = $this->package($village, ['name' => "$tag Murah", 'price' => 250000, 'tag_id' => 1]);
        $promo = $this->package($village, ['name' => "$tag Promo", 'price' => 900000, 'disc' => 450000, 'category_id' => 2]);
        $free = $this->package($village, ['name' => "$tag Gratis", 'price' => 0]);
        $pricey = $this->package($village, ['name' => "$tag Mahal", 'price' => 2000000]);
        $this->package($village, ['name' => "$tag Nonaktif", 'is_active' => 0]);

        $this->assertEqualsCanonicalizing([$cheap->name, $promo->name, $free->name, $pricey->name], $this->names("/en/tour-packages?q=$tag"));
        $this->assertSame([$free->name], $this->names("/en/tour-packages?q=$tag&price=free"));
        $this->assertSame([$promo->name], $this->names("/en/tour-packages?q=$tag&promo=1"));
        $this->assertSame([$promo->name], $this->names("/en/tour-packages?q=$tag&price=300k-700k"));
        $this->assertSame([$cheap->name], $this->names("/en/tour-packages?q=$tag&tag=1"));
        $this->assertSame([$promo->name], $this->names("/en/tour-packages?q=$tag&category=2"));
        $this->assertSame([$free->name, $cheap->name, $promo->name, $pricey->name], $this->names("/en/tour-packages?q=$tag&sort=price_asc"));
        $this->assertCount(4, $this->names("/en/tour-packages?q=$tag&price=hack&sort=drop"));

        $this->get("http://localhost/id/tour-packages?q=$tag&promo=1")
            ->assertSee('Rp 450.000')->assertSee('Rp 900.000')->assertSee('-50%')->assertSee('Sedang promo');
    }

    public function test_village_filters_and_counts(): void
    {
        $all = $this->names('/en/village?sort=name', 'id');
        $withHomestay = $this->get('http://localhost/en/village?has_homestay=1')->assertOk()->viewData('village');

        $this->assertNotEmpty($all);
        $this->assertTrue(collect($withHomestay->items())->every(fn ($v) => $v->homestays_count > 0));

        $first = VillageDetail::whereIn('user_id', $all)->firstOrFail();
        $found = $this->get('http://localhost/en/village?q='.urlencode($first->village_name))->viewData('village');
        $this->assertContains($first->user_id, collect($found->items())->pluck('id')->all());
    }

    public function test_events_default_to_upcoming(): void
    {
        $tag = 'Ze'.uniqid();
        foreach (['Upcoming' => [10, 50000, 0], 'Past' => [-10, 0, 1]] as $label => [$days, $price, $free]) {
            $event = Event::create([
                'category_id' => 1, 'name' => "$tag $label", 'description' => 'x', 'price' => $price, 'disc' => 0,
                'location' => 'Desa Uji', 'date_event' => now()->addDays($days)->toDateString(), 'is_active' => 1,
                'is_free' => $free, 'is_paywish' => 0, 'slug' => 'ze-'.uniqid(),
            ]);
            $this->eventIds[] = $event->id;
        }

        $this->assertSame(["$tag Upcoming"], $this->names("/en/events?q=$tag"));
        $this->assertSame(["$tag Past"], $this->names("/en/events?q=$tag&when=past"));
        $this->assertCount(2, $this->names("/en/events?q=$tag&when=all"));
        $this->assertSame(["$tag Past"], $this->names("/en/events?q=$tag&when=all&price=free"));
        $this->get("http://localhost/id/events?q=$tag&when=past")->assertSee('Sudah lewat')->assertSee('Gratis');
    }

    public function test_news_search_and_year(): void
    {
        $tag = 'Zn'.uniqid();
        $this->postIds[] = DB::table('post')->insertGetId([
            'post_title' => "$tag Artikel 2021", 'post_content' => '<p>Isi artikel uji.</p>', 'post_thumbnail' => 'x.jpg',
            'isPublished' => 1, 'post_author' => \App\Models\User::where('role_id', 1)->value('id'), 'slug' => 'zn-'.uniqid(), 'created_at' => '2021-05-01 10:00:00', 'updated_at' => now(),
        ]);

        $this->assertSame(["$tag Artikel 2021"], $this->names("/en/news?q=$tag", 'post_title'));
        $this->assertSame(["$tag Artikel 2021"], $this->names("/en/news?q=$tag&year=2021", 'post_title'));
        $this->assertSame([], $this->names("/en/news?q=$tag&year=2022", 'post_title'));

        // Artikel utama (besar) hanya di halaman pertama tanpa filter.
        $this->get('http://localhost/id/news')->assertSee('Baca artikel');
        $this->get("http://localhost/id/news?q=$tag")->assertDontSee('Baca artikel');
    }
}
