<?php

namespace App\Models\Acl;

use App\Models\Admin\Audit;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Contractor extends Model implements AuditableContract
{
    use AuditTrait, Auditable;

    protected $fillable = ['uuid', 'name', 'fantasy_name', 'cpf_cnpj', 'type_person', 'active', 'deleted', 'logo', 'phone', 'email'];

    public function generateTags(): array
    {
        return [
            $this->uuid ?? $this->id
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
