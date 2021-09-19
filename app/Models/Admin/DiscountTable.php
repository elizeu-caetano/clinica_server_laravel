<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class DiscountTable extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'active', 'deleted', 'contractor_id'];

    public function generateTags(): array
    {
        return [
            'Tabela de Descontos'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function procedures()
    {
        return $this->belongsToMany(Procedure::class);
    }
}
