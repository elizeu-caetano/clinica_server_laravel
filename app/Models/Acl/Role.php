<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['uuid', 'name', 'description', 'contractor_id', 'active', 'admin', 'deleted'];

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
