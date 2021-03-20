<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = ['uuid', 'name', 'fantasy_name', 'cpf_cnpj', 'type_person', 'active', 'deleted', 'logo'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Roles::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }
}
