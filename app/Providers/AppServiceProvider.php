<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Skladchina;
use App\Models\User;
use App\Models\Category;
use App\Observers\FlushResponseCacheObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

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

        View::composer('*', function ($view) {
            if (Str::startsWith(Request::path(), 'admin')) {
                return;
            }

            $segments = Request::segments();
            $breadcrumbs = [['url' => route('home'), 'label' => 'Главная']];

            $path = '';
            foreach ($segments as $index => $segment) {
                $path .= '/' . $segment;
                $breadcrumbs[] = [
                    'url' => $index < count($segments) - 1 ? url($path) : null,
                    'label' => ucfirst(str_replace('-', ' ', $segment)),
                ];
            }

            $data = $view->getData();
            if (isset($data['category'])) {
                $breadcrumbs[array_key_last($breadcrumbs)]['label'] = $data['category']->name;
            }
            if (isset($data['skladchina'])) {
                $breadcrumbs[array_key_last($breadcrumbs)]['label'] = $data['skladchina']->title;
            }
            if (isset($data['pageTitle'])) {
                $breadcrumbs[array_key_last($breadcrumbs)]['label'] = $data['pageTitle'];
            }

            $view->with('autoBreadcrumbs', $breadcrumbs);
        });

        View::composer('layouts.app', function ($view) {
            if (Str::startsWith(Request::path(), 'admin')) {
                return;
            }

            $view->with('headerCategories', Category::orderBy('name')->get());
        });
    }
}
