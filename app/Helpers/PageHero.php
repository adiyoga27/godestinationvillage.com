<?php

namespace App\Helpers;

use App\Models\PageHero as PageHeroModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Helper hero banner tiap halaman (komponen x-partials.page-hero).
 *
 * Judul, sub-judul, dan gambar latar tiap halaman bisa diubah dari
 * admin panel. Bila halaman belum diatur / field dikosongkan,
 * nilai bawaan dari blade tetap dipakai.
 */
class PageHero
{
    public const CACHE_KEY = 'page.heroes';

    /**
     * Semua hero, keyed by `key`.
     *
     * @return \Illuminate\Support\Collection<string, \App\Models\PageHero>
     */
    public static function all()
    {
        return Cache::remember(
            static::CACHE_KEY,
            3600,
            fn () => PageHeroModel::orderBy('id')->get()->keyBy('key')
        );
    }

    /**
     * Teks hero sesuai bahasa aktif (id/en).
     * Urutan fallback: teks bahasa aktif -> teks English -> default blade.
     */
    public static function text(?string $key, string $field, $default = ''): string
    {
        if (! $key) {
            return (string) $default;
        }

        $hero = static::all()->get($key);

        if (! $hero) {
            return (string) $default;
        }

        $isId = app()->getLocale() === 'id';
        $localized = $isId ? ($hero->{$field.'_id'} ?? null) : null;
        $english = $hero->{$field} ?? null;

        $value = $localized ?: $english;

        return ($value === null || $value === '') ? (string) $default : $value;
    }

    /**
     * Gambar latar hero. Mendukung path asset bawaan (assets/...),
     * URL penuh, atau nama file upload di storage/page-heroes/.
     */
    public static function image(?string $key, ?string $default = null): ?string
    {
        $image = $default;

        if ($key && ($hero = static::all()->get($key)) && ! empty($hero->image)) {
            $image = $hero->image;
        }

        if (! $image) {
            return null;
        }

        if (Str::startsWith($image, ['http://', 'https://', 'assets/', 'storage/'])) {
            return asset($image);
        }

        return asset('storage/page-heroes/'.$image);
    }

    /**
     * Bersihkan cache (dipanggil setiap ada perubahan dari admin).
     */
    public static function flush(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
