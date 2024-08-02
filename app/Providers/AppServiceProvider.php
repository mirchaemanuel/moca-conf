<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
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
        /**
         * For brevity in this demo project, we will disable Laravel's mass assignment protection.
         * Filament only saves valid data to models so the models can be unguarded safely
         */
        Model::unguard();
    }
}
