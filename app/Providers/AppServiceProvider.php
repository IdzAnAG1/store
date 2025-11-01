<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        User::observe(UserObserver::class);
        View::composer(['components.header', 'layouts.main', '*'], function ($view) {
            $rootCategories = Cache::remember('rootCategories', 600, function () {
                return Category::with(['children.children'])
                    ->whereNull('parent_category_id')
                    ->orderBy('category_name')
                    ->get();
            });

            $view->with('rootCategories', $rootCategories);
        });
    }
}
