<?php

namespace App\Observers;

use App\Models\Acl\Permission;

class PermissionObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = false;

    /**
     * Handle the Permission "created" event.
     *
     * @param  \App\Models\Acl\Permission  $permission
     * @return void
     */
    public function created(Permission $permission)
    {
        $plans = $permission->plans->get();

        foreach ($plans as $plan) {
           $roles = $plan->roles->get();
           foreach ($roles as $role) {
               if ($role->admin) {
                    $role->permissions()->attach($permission->id);
               }
           }
        }
    }

    /**
     * Handle the Permission "updated" event.
     *
     * @param  \App\Models\Acl\Permission  $permission
     * @return void
     */
    public function updated(Permission $permission)
    {
        //
    }

    /**
     * Handle the Permission "deleted" event.
     *
     * @param  \App\Models\Acl\Permission  $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        //
    }

    /**
     * Handle the Permission "restored" event.
     *
     * @param  \App\Models\Acl\Permission  $permission
     * @return void
     */
    public function restored(Permission $permission)
    {
        //
    }

    /**
     * Handle the Permission "force deleted" event.
     *
     * @param  \App\Models\Acl\Permission  $permission
     * @return void
     */
    public function forceDeleted(Permission $permission)
    {
        //
    }
}
