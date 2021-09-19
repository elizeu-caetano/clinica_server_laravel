<?php

namespace App\Observers\Admin;

use App\Models\Admin\Procedure;
use App\Models\Admin\DiscountTable;
use Illuminate\Support\Facades\Auth;

class DiscountTableObserver
{
    /**
     * Handle the DiscountTable "created" event.
     *
     * @param  \App\Models\DiscountTable  $discountTable
     * @return void
     */
    public function created(DiscountTable $discountTable)
    {
        $procedures = Procedure::where('contractor_id', Auth::user()->contractor_id)->get();

        foreach ($procedures as $procedure) {
            $discountTable->procedures()->attach([$procedure->id => ['price' => $procedure->price]]);
        }

    }
}
