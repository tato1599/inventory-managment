<?php

namespace App\Providers;

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
        // Add manual registration for MaryUI components if prefix issues persist
        \Illuminate\Support\Facades\Blade::component('mary-select', \Mary\View\Components\Select::class);
        \Illuminate\Support\Facades\Blade::component('select', \Mary\View\Components\Select::class);
    }
}
