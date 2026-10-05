<?php

namespace App\Providers;

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
    }
}
