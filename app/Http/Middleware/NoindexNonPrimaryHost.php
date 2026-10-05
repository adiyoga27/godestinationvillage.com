<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Salinan situs di host selain domain utama (staging, *.codingaja.com, dll.)
 * dikirimi X-Robots-Tag noindex agar tidak ikut terindeks mesin pencari.
 * Lapis kedua selain aturan yang sama di public/.htaccess.
 */
class NoindexNonPrimaryHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $primary = strtolower((string) config('seo.primary_host'));
        if ($primary !== '' && strtolower($request->getHost()) !== $primary) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
