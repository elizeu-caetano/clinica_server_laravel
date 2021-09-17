<?php

namespace App\Models\JobMed;

use App\Models\Admin\Company;
use App\Models\Admin\Procedure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class CompanyOccupation extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['company_id', 'occupation_id'];

    public function generateTags(): array
    {
        return [
            'Funções da Empresa'
        ];
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function dangers()
    {
        return $this->belongsToMany(Danger::class);
    }

    public function procedures()
    {
        return $this->belongsToMany(Procedure::class);
    }
}
