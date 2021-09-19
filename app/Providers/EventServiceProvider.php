<?php

namespace App\Providers;

use App\Events\NewUser;
use App\Listeners\NewUserPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Models\Acl\{
    Contractor
};

use App\Observers\{
    ContractorObserver
};

use App\Models\Admin\{
    DiscountTable
};

use App\Observers\Admin\{
    DiscountTableObserver
};

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],

        NewUser::class => [
            NewUserPassword::class,
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Contractor::observe(ContractorObserver::class);
        DiscountTable::observe(DiscountTableObserver::class);
    }
}
