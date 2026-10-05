<?php

namespace App\Providers;

use App\Support\Locales;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Canonical, og:url & sitemap selalu https di produksi.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Semua url()/route() ke halaman publik otomatis berawalan bahasa aktif (/id/faq, /en/faq).
        URL::formatPathUsing(fn (string $path) => Locales::localizePath($path));
        URL::defaults(['locale' => Locales::DEFAULT]);
    }
}
