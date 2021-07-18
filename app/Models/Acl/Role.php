<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['uuid', 'name', 'description', 'contractor_id', 'active', 'admin', 'deleted'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
}
