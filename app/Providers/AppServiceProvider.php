<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Set locale Indonesia untuk translatedFormat()
        App::setLocale('id');

        // Gunakan Tailwind pagination
        Paginator::useTailwind();

        // Prevent lazy loading di development
        Model::preventLazyLoading(!app()->isProduction());

        // Force HTTPS di production
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
