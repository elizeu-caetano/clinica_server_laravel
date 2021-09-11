<?php

namespace App\Models\JobMed;

use App\Models\Admin\Audit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Danger extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['name', 'active', 'deleted'];

    public function generateTags(): array
    {
        return [
            'Riscos'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function occupations()
    {
        return $this->belongsToMany(Occupation::class);
    }
}
