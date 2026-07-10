<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        // Le serveur MySQL partagé utilise MyISAM par défaut (limite de clé à 1000
        // octets) : sans cette limite, un index unique sur un varchar(255) utf8mb4 échoue.
        Schema::defaultStringLength(191);
    }
}
