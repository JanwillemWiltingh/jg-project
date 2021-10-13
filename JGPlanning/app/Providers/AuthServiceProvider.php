<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
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

        Gate:: define('admin-clocker', function (User $user){
            if($user['role_id'] == 1 || $user['role_id'] == 3) {
                return True;
            }
            return False;
        });
        Gate:: define('admin-users', function (User $user){
            if($user['role_id'] == 1 || $user['role_id'] == 3) {
                return True;
            }
            return False;
        });

        Gate:: define('admin-users', function (User $user){
            if($user['role_id'] == 1 || $user['role_id'] == 3) {
                return True;
            }
            return False;
        });

        Gate:: define('admin-beschikbaarheid', function (User $user){
            if($user['role_id'] == 1 || $user['role_id'] == 3) {
                return True;
            }
            return False;
        });

        Gate:: define('employee-clocker', function (User $user){
            if($user['role_id'] == 2) {
                return True;
            }
            return False;
        });

        Gate:: define('employee-rooster', function (User $user){
            if($user['role_id'] == 2) {
                return True;
            }
            return False;
        });
    }
}
