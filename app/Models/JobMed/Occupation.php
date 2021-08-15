<?php

namespace App\Models\JobMed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'code', 'active', 'deleted', 'company_id'];

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
