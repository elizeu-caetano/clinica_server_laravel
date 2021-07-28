<?php

namespace App\Models\Acl;

use App\Models\Admin\Audit;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Role extends Model implements AuditableContract
{
    use Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'description', 'contractor_id', 'active', 'admin', 'deleted'];

    public function generateTags(): array
    {
        return [
            'Papéis'
        ];
    }

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

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
