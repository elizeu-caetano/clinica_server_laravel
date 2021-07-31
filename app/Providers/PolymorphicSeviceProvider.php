<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class PolymorphicSeviceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => 'App\Models\Acl\User',
            'contractors' => 'App\Models\Acl\Contractor',
            'plans' => 'App\Models\Acl\Plan',
            'permissions' => 'App\Models\Acl\Permission',
            'roles' => 'App\Models\Acl\Role',
            'phones' => 'App\Models\Admin\Phone',
            'emails' => 'App\Models\Admin\Email',
            'companies' => 'App\Models\Admin\Company'
        ]);
    }
}
