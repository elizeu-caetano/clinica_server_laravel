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
            'addresses' => 'App\Models\Admin\Address',
            'companies' => 'App\Models\Admin\Company',
            'contractors' => 'App\Models\Acl\Contractor',
            'dangers' => 'App\Models\JobMed\Danger',
            'discount_tables' => 'App\Models\Admin\DiscountTable',
            'emails' => 'App\Models\Admin\Email',
            'occupations' => 'App\Models\JobMed\Occupation',
            'permissions' => 'App\Models\Acl\Permission',
            'plans' => 'App\Models\Acl\Plan',
            'phones' => 'App\Models\Admin\Phone',
            'procedure' => 'App\Models\Admin\Procedure',
            'procedure_groups' => 'App\Models\Admin\ProcedureGroup',
            'roles' => 'App\Models\Acl\Role',
            'users' => 'App\Models\Acl\User'
        ]);
    }
}
