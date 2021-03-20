<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class PhonesUser extends Model
{
    protected $fillable = ['phone', 'type', 'deleted', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
