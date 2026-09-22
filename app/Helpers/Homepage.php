<?php

namespace App\Helpers;

use App\Models\HomepageSection;
use App\Models\HomepageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Helper konten dinamis homepage.
 *
 * Semua teks section (eyebrow/judul/subjudul/tombol), gambar section,
 * dan daftar Our Services diambil dari database dan bisa diubah
 * dari admin panel. Hasilnya di-cache dan otomatis dibersihkan
 * setiap ada perubahan dari admin.
 */
class Homepage
{
    public const SECTIONS_CACHE_KEY = 'homepage.sections';

    public const SERVICES_CACHE_KEY = 'homepage.services';

    /**
     * Semua section terurut, keyed by `key`.
     *
     * @return \Illuminate\Support\Collection<string, \App\Models\HomepageSection>
     */
    public static function sections()
    {
        return Cache::remember(
            static::SECTIONS_CACHE_KEY,
            3600,
            fn () => HomepageSection::orderBy('sort_order')->get()->keyBy('key')
        );
    }

    /**
     * Item Our Services yang aktif, terurut.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\HomepageService>
     */
    public static function services()
    {
        return Cache::remember(
            static::SERVICES_CACHE_KEY,
            3600,
            fn () => HomepageService::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
        );
    }

    /**
     * Apakah section tampil? Section yang belum ada di DB dianggap tampil
     * (fallback aman agar halaman tidak kosong).
     */
    public static function visible(string $key): bool
    {
        $section = static::sections()->get($key);

        return $section ? (bool) $section->is_active : true;
    }

    /**
     * Teks section sesuai bahasa aktif (id/en).
     * Urutan fallback: teks bahasa aktif -> teks English -> default.
     */
    public static function text(string $key, string $field, string $default = ''): string
    {
        $section = static::sections()->get($key);

        if (! $section) {
            return $default;
        }

        $isId = app()->getLocale() === 'id';
        $localized = $isId ? ($section->{$field.'_id'} ?? null) : null;
        $english = $section->{$field} ?? null;

        return $localized ?: ($english ?: $default);
    }

    /**
     * URL gambar section. Mendukung path asset bawaan (assets/...),
     * URL penuh, atau nama file upload di storage/homepage-sections/.
     */
    public static function image(string $key, ?string $fallback = null): ?string
    {
        $section = static::sections()->get($key);
        $image = $section?->image ?: $fallback;

        if (! $image) {
            return null;
        }

        return static::resolveImage($image, 'homepage-sections');
    }

    /**
     * URL gambar item service.
     */
    public static function serviceImage(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        return static::resolveImage($image, 'homepage-services');
    }

    protected static function resolveImage(string $image, string $diskDir): string
    {
        if (Str::startsWith($image, ['http://', 'https://', 'assets/', 'storage/'])) {
            return asset($image);
        }

        return asset('storage/'.$diskDir.'/'.$image);
    }

    /**
     * URL tombol: mendukung URL penuh atau path internal (tanpa leading slash).
     */
    public static function url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '#'])) {
            return $url;
        }

        return url($url);
    }

    /**
     * Tombol section: label sesuai bahasa + URL (fallback ke default).
     *
     * @return array{label: string, url: string|null}
     */
    public static function button(string $key, int $number = 1, string $defaultLabel = '', ?string $defaultUrl = null): array
    {
        $prefix = $number === 1 ? 'button_' : 'button2_';
        $section = static::sections()->get($key);

        $label = null;
        $url = null;

        if ($section) {
            $isId = app()->getLocale() === 'id';
            $label = ($isId ? $section->{$prefix.'label_id'} : null) ?: $section->{$prefix.'label'};
            $url = static::url($section->{$prefix.'url'});
        }

        return [
            'label' => $label ?: $defaultLabel,
            'url' => $url ?: $defaultUrl,
        ];
    }

    /**
     * Bersihkan cache homepage (dipanggil setiap ada perubahan dari admin).
     */
    public static function flush(): void
    {
        Cache::forget(static::SECTIONS_CACHE_KEY);
        Cache::forget(static::SERVICES_CACHE_KEY);
    }
}
