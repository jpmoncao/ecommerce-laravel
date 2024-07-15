<?php

namespace App\Providers;

use App\Models\Carts;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('access-cart', function (User $user, string $id) {
            return $user->id_user === $id;
        });

        Gate::define('access-user', function (User $user, string $id) {
            return $user->id_user === $id;
        });
    }
}
