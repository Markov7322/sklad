<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Skladchina;
use App\Models\User;
use App\Observers\FlushResponseCacheObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Skladchina::observe(FlushResponseCacheObserver::class);
        User::observe(FlushResponseCacheObserver::class);
    }
}
