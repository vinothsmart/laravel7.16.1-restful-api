<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
            'add-user' => 'Create a new user for a specific role',
            'list-roles' => 'List all roles',
            'manage-role' => 'Create, read, update and delete roles (CRUD)',
            'manage-account' => 'Read your account data, id, name, email, if verified and if admin (cannot read password). Modify your account data(email and password)',
            'read-general' => 'Read general information like getting roles, listed users',
        ]);
    }
}
