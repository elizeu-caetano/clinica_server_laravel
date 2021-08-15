<?php

namespace App\Models\JobMed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class MOccupation extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'code', 'active', 'deleted'];

    public function generateTags(): array
    {
        return [
            'Funções Padrão'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
