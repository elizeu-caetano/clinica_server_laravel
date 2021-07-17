<?php

namespace App\Providers;
use App\Models\Acl\{
    Permission,
    User
};

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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
        Passport::routes();

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            Gate::define($permission->permission, function (User $user) use ($permission) {
                return $user->hasPermission($permission->permission);
            });
        }

        Gate::before(function (User $user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
