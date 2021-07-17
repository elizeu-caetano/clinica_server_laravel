<?php

namespace App\Providers;

use App\Models\Acl\{
    Contractor
};
use App\Observers\{
    ContractorObserver
};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Contractor::observe(ContractorObserver::class);
    }
}
