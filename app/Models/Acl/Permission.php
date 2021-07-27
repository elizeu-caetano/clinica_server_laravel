<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Permission extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['name', 'permission'];

    public function generateTags(): array
    {
        return [
            $this->uuid ?? $this->id
        ];
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
