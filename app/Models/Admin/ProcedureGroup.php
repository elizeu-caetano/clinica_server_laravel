<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class ProcedureGroup extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'deleted', 'contractor_id'];

    public function generateTags(): array
    {
        return [
            'Grupo de Procedimentos'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

}
