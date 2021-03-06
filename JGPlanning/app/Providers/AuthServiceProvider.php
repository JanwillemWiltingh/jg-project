<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    private $roles;
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-clocker', function (User $user){
            return !($user['role_id'] == Role::getRoleID('employee'));
        });

        Gate::define('admin-users', function (User $user){
            return !($user['role_id'] == Role::getRoleID('employee'));
        });

        Gate::define('admin-users', function (User $user){
            return !($user['role_id'] == Role::getRoleID('employee'));
        });

        Gate::define('admin-beschikbaarheid', function (User $user){
            return !($user['role_id'] == Role::getRoleID('employee'));
        });

        Gate::define('employee-clocker', function (User $user) {
            return $user['role_id'] == Role::getRoleID('employee');
        });

        Gate:: define('admin-logout', function (User $user){
            if($user['role_id'] == Role::getRoleID('admin') || $user['role_id'] == Role::getRoleID('maintainer')) {
                return True;
            }
            return False;
        });

        Gate:: define('employee-clocker', function (User $user){
            if($user['role_id'] == Role::getRoleID('employee')) {
                return True;
            }
            return False;
        });

        Gate::define('employee-rooster', function (User $user){
            return $user['role_id'] == Role::getRoleID('employee');
        });

        Gate::define('employee-clock', function (User $user){
            return $user['role_id'] == Role::getRoleID('employee');
        });
    }
}
