<?php

namespace App\Providers;

use App\Policies\ProjectUserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('inviteMember', [ProjectUserPolicy::class, 'inviteMember']);
    }
}
