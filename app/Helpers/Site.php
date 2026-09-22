<?php

namespace App\Helpers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Helper data umum website (alamat, telepon, email, sosmed).
 *
 * Nilai awal diambil dari data yang selama ini hardcoded di website,
 * bisa diubah dari admin panel (Kelola Website > Pengaturan Website).
 */
class Site
{
    public const CACHE_KEY = 'site.settings';

    /**
     * Semua setting sebagai array key => value.
     */
    public static function all(): array
    {
        return Cache::remember(
            static::CACHE_KEY,
            3600,
            fn () => SiteSetting::pluck('value', 'key')->all()
        );
    }

    /**
     * Ambil satu setting dengan nilai default bila belum ada di DB.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        return static::all()[$key] ?? $default;
    }

    /**
     * Nomor telepon tanpa "+" untuk link WhatsApp (wa.me/...).
     */
    public static function whatsapp(?string $default = null): ?string
    {
        return ltrim(static::get('phone', $default) ?? '', '+');
    }

    /**
     * Bersihkan cache (dipanggil setiap ada perubahan dari admin).
     */
    public static function flush(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
