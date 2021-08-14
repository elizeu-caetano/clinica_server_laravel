<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Procedure extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'price', 'commission', 'material', 'is_percentage', 'active', 'deleted',
                            'is_print', 'procedures_group_id', 'contractor_id'];

    public function generateTags(): array
    {
        return [
            'Procedimentos'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
