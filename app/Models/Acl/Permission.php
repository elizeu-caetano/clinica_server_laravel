<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'permission'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
