<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('delete-restaurant', function ($pigeon) {
           return $pigeon->is_super
               ? Response::allow()
               : Response::deny('You must be a super admin.');
        });

        Gate::define('license-is-created', function ($user) {
            if (!$user->relationLoaded('drivers_license')) {
                $user->load('drivers_license');
            }
            return $user->drivers_license !== null;
        });

        Gate::define('driver-can-reserve', function ($user) {
            if (!$user->relationLoaded('reserved_order')) {
                $user->load('reserved_order');
            }
            return !$user->reserved_order->first();
        });
        
        Gate::define('driver-has-vehicle', function ($user) {
            if (!$user->relationLoaded('vehicle')) {
                $user->load('vehicle');
            }
            return $user->vehicle !== null;
        });
    }
}
