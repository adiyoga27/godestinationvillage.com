<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bahasa dari awalan URL (/id/..., /en/...) untuk halaman publik; halaman lain
 * (booking, pembayaran, admin) memakai pilihan terakhir yang tersimpan di session.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        [$urlLocale] = Locales::split($request->getPathInfo());

        if ($urlLocale !== null) {
            $locale = $urlLocale;
            Session::put('locale', $locale);
        } else {
            $locale = Session::get('locale', Locales::DEFAULT);
            if (! in_array($locale, Locales::SUPPORTED, true)) {
                $locale = Locales::DEFAULT;
            }
        }

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // {locale} hanya awalan URL; controller tidak menerimanya sebagai parameter.
        $route = $request->route();
        if ($route && str_starts_with($route->uri(), '{locale}')) {
            $route->forgetParameter('locale');
        }

        return $next($request);
    }
}
