<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Struktur URL per bahasa: halaman publik (konten/SEO) memakai awalan /id/ atau /en/,
 * mis. /id/faq & /en/faq. Default /id. Halaman transaksi (booking, pembayaran, login,
 * webhook) & admin tetap tanpa awalan — bahasanya mengikuti session terakhir.
 *
 * Satu sumber kebenaran untuk: routes/web.php, generator URL (URL::formatPathUsing),
 * redirect URL lama (RedirectToLocalizedUrl), pemilih bahasa, hreflang & sitemap.
 */
class Locales
{
    public const SUPPORTED = ['id', 'en'];

    public const DEFAULT = 'id';

    /** Segmen pertama path halaman berbahasa. '' = beranda. */
    public const SEGMENTS = [
        '', 'services', 'faq', 'contact', 'company-profile', 'tentang-godevi', 'about-godevi',
        'village', 'tour-packages', 'events', 'homestay', 'category-package', 'daftar-desa',
        'asesmen', 'term', 'our-team', 'v-founding', 'v-board', 'v-portofolio', 'our-partner',
        'news', 'search',
    ];

    /** Halaman yang isinya hanya berbahasa Indonesia: selalu /id/..., /en/... dialihkan. */
    public const ID_ONLY_SEGMENTS = ['asesmen'];

    public static function current(): string
    {
        $locale = App::getLocale();

        return in_array($locale, self::SUPPORTED, true) ? $locale : self::DEFAULT;
    }

    /** Path ('/faq', 'village/x') termasuk halaman berbahasa? (tanpa awalan bahasa) */
    public static function isLocalizedPath(string $path): bool
    {
        return in_array(self::firstSegment($path), self::SEGMENTS, true);
    }

    /**
     * Tambahkan awalan bahasa pada path halaman berbahasa. Path lain (admin, storage,
     * booking, dst.) & path yang sudah berawalan bahasa dikembalikan apa adanya.
     */
    public static function localizePath(string $path, ?string $locale = null): string
    {
        $path = '/'.ltrim($path, '/');
        $first = self::firstSegment($path);

        if (in_array($first, self::SUPPORTED, true) || ! in_array($first, self::SEGMENTS, true)) {
            return $path;
        }

        $locale = in_array($first, self::ID_ONLY_SEGMENTS, true) ? 'id' : ($locale ?? self::current());

        return rtrim('/'.$locale.$path, '/');
    }

    /** Pisahkan awalan bahasa: '/en/faq' → ['en', '/faq']; '/booking/1' → [null, '/booking/1']. */
    public static function split(string $path): array
    {
        $path = '/'.ltrim($path, '/');
        $first = self::firstSegment($path);

        if (! in_array($first, self::SUPPORTED, true)) {
            return [null, $path];
        }

        $rest = substr($path, strlen($first) + 1);

        return [$first, $rest === '' ? '/' : $rest];
    }

    /** Halaman saat ini punya versi per bahasa (untuk hreflang & pemilih bahasa)? */
    public static function hasAlternates(Request $request): bool
    {
        [$locale, $rest] = self::split($request->getPathInfo());

        return $locale !== null
            && self::isLocalizedPath($rest)
            && ! in_array(self::firstSegment($rest), self::ID_ONLY_SEGMENTS, true);
    }

    /**
     * URL halaman saat ini dalam bahasa lain. Halaman tanpa versi bahasa memakai
     * /locale/{locale} (simpan pilihan ke session, lalu kembali).
     */
    public static function switchUrl(string $locale, ?Request $request = null): string
    {
        $request ??= request();

        if (! self::hasAlternates($request)) {
            return url('locale/'.$locale);
        }

        [, $rest] = self::split($request->getPathInfo());
        $query = $request->getQueryString();

        return url(self::localizePath($rest, $locale)).($query ? '?'.$query : '');
    }

    /** hreflang untuk halaman saat ini: ['id' => url, 'en' => url, 'x-default' => url]. */
    public static function alternates(?Request $request = null): array
    {
        $request ??= request();

        if (! self::hasAlternates($request)) {
            return [];
        }

        [, $rest] = self::split($request->getPathInfo());
        $urls = [];
        foreach (self::SUPPORTED as $locale) {
            $urls[$locale] = url(self::localizePath($rest, $locale));
        }
        $urls['x-default'] = $urls[self::DEFAULT];

        return $urls;
    }

    private static function firstSegment(string $path): string
    {
        return explode('/', trim(parse_url($path, PHP_URL_PATH) ?? '', '/'))[0];
    }
}
