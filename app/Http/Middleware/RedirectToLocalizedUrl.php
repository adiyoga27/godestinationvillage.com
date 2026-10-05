<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * URL halaman publik tanpa awalan bahasa (/faq, /village/x) → /id/... (301).
 * /en/asesmen/... (isi hanya Bahasa Indonesia) → /id/asesmen/....
 * Non-GET memakai 308 agar method & isi form ikut (form lama yang masih terbuka).
 */
class RedirectToLocalizedUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();
        [$locale, $rest] = Locales::split($path);
        $target = null;

        if ($locale === null && $path !== '/' && Locales::isLocalizedPath($path)) {
            $target = Locales::localizePath($path, Locales::DEFAULT);
        } elseif ($locale !== null && $locale !== 'id' && in_array(explode('/', trim($rest, '/'))[0], Locales::ID_ONLY_SEGMENTS, true)) {
            $target = '/id'.$rest;
        }

        if ($target === null) {
            return $next($request);
        }

        $query = $request->getQueryString();

        return redirect()->to(
            $request->getSchemeAndHttpHost().$request->getBaseUrl().$target.($query ? '?'.$query : ''),
            $request->isMethod('GET') || $request->isMethod('HEAD') ? 301 : 308
        );
    }
}
