<?php

namespace App\Models\JobMed;

use App\Models\Admin\Audit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Occupation extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['code', 'name', 'type', 'active', 'deleted'];

    public function generateTags(): array
    {
        return [
            'Funções'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
