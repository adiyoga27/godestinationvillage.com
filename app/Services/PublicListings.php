<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Category;
use App\Models\CategoryEvent;
use App\Models\Event;
use App\Models\Package;
use App\Models\Tag;
use App\Models\User;
use App\Models\VillageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Query + filter untuk halaman daftar publik: /tour-packages, /village, /events, /news.
 * (Homestay: HomeStayServices::search.) Semua filter dibaca dari query string & divalidasi di sini.
 */
class PublicListings
{
    /** Rentang harga akhir (setelah diskon) dipakai paket wisata: key => [min, max]. */
    public const PRICE_RANGES = [
        'free' => [0, 0],
        'under-300k' => [1, 299999],
        '300k-700k' => [300000, 700000],
        '700k-1500k' => [700001, 1500000],
        'over-1500k' => [1500001, null],
    ];

    // ---------------------------------------------------------------- paket wisata

    public static function packageFilters(Request $request): array
    {
        return [
            'q' => self::text($request),
            'village' => $request->integer('village') ?: null,
            'category' => $request->integer('category') ?: null,
            'tag' => $request->integer('tag') ?: null,
            'price' => self::oneOf($request->query('price'), array_keys(self::PRICE_RANGES)),
            'promo' => $request->boolean('promo'),
            'sort' => self::oneOf($request->query('sort'), ['newest', 'price_asc', 'price_desc', 'name'], 'newest'),
        ];
    }

    public static function packages(array $f, int $perPage = 9)
    {
        $final = 'CASE WHEN packages.disc > 0 THEN packages.disc ELSE packages.price END';

        // Desa aktif dicek lewat pemilik desa (village_details.user_id), seperti sebelumnya.
        $query = Package::query()
            ->select('packages.*', 'categories.name as cat_name', 'village_details.village_name as vil_name')
            ->with('translate')
            ->leftJoin('village_details', 'village_details.id', '=', 'packages.village_id')
            ->leftJoin('users', 'users.id', '=', 'village_details.user_id')
            ->leftJoin('categories', 'categories.id', '=', 'packages.category_id')
            ->where('users.is_active', '1')
            ->where('packages.is_active', '1');

        if ($f['q']) {
            $term = self::like($f['q']);
            $query->where(fn ($q) => $q->where('packages.name', 'like', $term)
                ->orWhere('village_details.village_name', 'like', $term)
                ->orWhereHas('translate', fn ($t) => $t->where('name', 'like', $term)));
        }
        $f['village'] && $query->where('packages.village_id', $f['village']);
        $f['category'] && $query->where('packages.category_id', $f['category']);
        $f['tag'] && $query->where('packages.tag_id', $f['tag']);
        $f['promo'] && $query->where('packages.disc', '>', 0)->whereColumn('packages.disc', '<', 'packages.price');
        if ($range = self::PRICE_RANGES[$f['price'] ?? ''] ?? null) {
            $query->whereRaw("$final >= ?", [$range[0]]);
            $range[1] !== null && $query->whereRaw("$final <= ?", [$range[1]]);
        }

        match ($f['sort']) {
            'price_asc' => $query->orderByRaw("$final asc"),
            'price_desc' => $query->orderByRaw("$final desc"),
            'name' => $query->orderBy('packages.name'),
            default => $query->orderByDesc('packages.created_at'),
        };

        return $query->orderByDesc('packages.id')->paginate($perPage)->withQueryString();
    }

    public static function packageOptions(): array
    {
        $active = Package::query()->where('is_active', 1);

        return [
            'villages' => VillageDetail::whereIn('id', (clone $active)->select('village_id'))
                ->whereHas('user', fn ($u) => $u->where('is_active', 1))
                ->orderBy('village_name')->pluck('village_name', 'id'),
            'categories' => Category::whereIn('id', (clone $active)->select('category_id'))->orderBy('id')->pluck('name', 'id'),
            'tags' => Tag::whereIn('id', (clone $active)->select('tag_id'))->orderBy('sort_order')->orderBy('id')->get(),
        ];
    }

    // ---------------------------------------------------------------- desa wisata

    public static function villageFilters(Request $request): array
    {
        return [
            'q' => self::text($request),
            'has_packages' => $request->boolean('has_packages'),
            'has_homestay' => $request->boolean('has_homestay'),
            'sort' => self::oneOf($request->query('sort'), ['popular', 'name', 'newest'], 'popular'),
        ];
    }

    public static function villages(array $f, int $perPage = 12)
    {
        $query = User::query()
            ->select('users.*')
            ->join('village_details', 'village_details.user_id', '=', 'users.id')
            ->whereNull('village_details.deleted_at')
            ->where('users.role_id', '2')
            ->where('users.is_active', '1')
            ->with('village_detail')
            ->selectSub(DB::table('packages')->selectRaw('count(*)')
                ->whereColumn('packages.village_id', 'village_details.id')->where('packages.is_active', 1)->whereNull('packages.deleted_at'), 'packages_count')
            ->selectSub(DB::table('homestay')->selectRaw('count(*)')
                ->whereColumn('homestay.village_id', 'village_details.id')->where('homestay.is_active', 1)->whereNull('homestay.deleted_at'), 'homestays_count');

        if ($f['q']) {
            $term = self::like($f['q']);
            $query->where(fn ($q) => $q->where('village_details.village_name', 'like', $term)
                ->orWhere('village_details.village_address', 'like', $term));
        }
        $f['has_packages'] && $query->having('packages_count', '>', 0);
        $f['has_homestay'] && $query->having('homestays_count', '>', 0);

        match ($f['sort']) {
            'name' => $query->orderBy('village_details.village_name'),
            'newest' => $query->orderByDesc('village_details.created_at'),
            default => $query->orderByDesc('packages_count')->orderByDesc('homestays_count')->orderBy('village_details.village_name'),
        };

        return $query->orderBy('users.id')->paginate($perPage)->withQueryString();
    }

    // ---------------------------------------------------------------- event

    public static function eventFilters(Request $request): array
    {
        return [
            'q' => self::text($request),
            'category' => $request->integer('category') ?: null,
            'when' => self::oneOf($request->query('when'), ['upcoming', 'past', 'all'], 'upcoming'),
            'price' => self::oneOf($request->query('price'), ['free', 'paid']),
            'sort' => self::oneOf($request->query('sort'), ['date', 'newest'], 'date'),
        ];
    }

    public static function events(array $f, int $perPage = 9)
    {
        $today = now()->toDateString();
        $query = Event::with(['translate', 'category'])->where('is_active', 1);

        if ($f['q']) {
            $term = self::like($f['q']);
            $query->where(fn ($q) => $q->where('name', 'like', $term)
                ->orWhere('location', 'like', $term)
                ->orWhereHas('translate', fn ($t) => $t->where('name', 'like', $term)));
        }
        $f['category'] && $query->where('category_id', $f['category']);
        // Event tanpa tanggal dianggap belum lewat.
        match ($f['when']) {
            'upcoming' => $query->where(fn ($q) => $q->whereNull('date_event')->orWhereDate('date_event', '>=', $today)),
            'past' => $query->whereDate('date_event', '<', $today),
            default => null,
        };
        match ($f['price']) {
            'free' => $query->where(fn ($q) => $q->where('is_free', 1)->orWhere('price', '<=', 0)),
            'paid' => $query->where('is_free', 0)->where('price', '>', 0),
            default => null,
        };

        if ($f['sort'] === 'newest') {
            $query->orderByDesc('created_at');
        } elseif ($f['when'] === 'past') {
            $query->orderByDesc('date_event');
        } else {
            $query->orderByRaw('date_event IS NULL')->orderBy('date_event');
        }

        return $query->orderByDesc('id')->paginate($perPage)->withQueryString();
    }

    public static function eventOptions(): array
    {
        return [
            'categories' => CategoryEvent::whereIn('id', Event::where('is_active', 1)->select('category_id'))
                ->orderBy('id')->pluck('name', 'id'),
        ];
    }

    // ---------------------------------------------------------------- berita

    public static function newsFilters(Request $request): array
    {
        $year = $request->integer('year');

        return [
            'q' => self::text($request),
            'year' => $year >= 2000 && $year <= (int) date('Y') + 1 ? $year : null,
            'sort' => self::oneOf($request->query('sort'), ['newest', 'oldest'], 'newest'),
        ];
    }

    public static function news(array $f, int $perPage = 9)
    {
        $query = Blog::where('isPublished', '1');

        if ($f['q']) {
            $term = self::like($f['q']);
            $query->where(fn ($q) => $q->where('post_title', 'like', $term)->orWhere('post_content', 'like', $term));
        }
        $f['year'] && $query->whereYear('created_at', $f['year']);

        // Artikel lama tanpa tanggal (created_at kosong) diurutkan lewat id.
        $f['sort'] === 'oldest'
            ? $query->orderBy('created_at')->orderBy('id')
            : $query->orderByDesc('created_at')->orderByDesc('id');

        return $query->paginate($perPage)->withQueryString();
    }

    public static function newsYears(): array
    {
        return Blog::where('isPublished', '1')->whereNotNull('created_at')
            ->selectRaw('YEAR(created_at) as y')->distinct()->orderByDesc('y')->pluck('y')->all();
    }

    // ---------------------------------------------------------------- util

    private static function text(Request $request): string
    {
        return mb_substr(trim((string) $request->query('q', '')), 0, 100);
    }

    private static function oneOf($value, array $allowed, ?string $default = null): ?string
    {
        return in_array($value, $allowed, true) ? $value : $default;
    }

    private static function like(string $term): string
    {
        return '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term).'%';
    }
}
