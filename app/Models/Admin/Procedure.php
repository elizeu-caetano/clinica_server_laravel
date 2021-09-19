<?php

namespace App\Models\Admin;

use App\Models\Acl\Contractor;
use App\Models\JobMed\Occupation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Procedure extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'price', 'commission', 'material', 'is_percentage', 'active', 'deleted',
                            'is_print', 'procedure_group_id', 'contractor_id'];

    public function generateTags(): array
    {
        return [
            'Procedimentos'
        ];
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '.', str_replace(array('R$',' ','.'), '', $value));
    }

    public function setCommissionAttribute($value)
    {
        $this->attributes['commission'] =  str_replace(',', '.', str_replace(array('R$',' ','.'), '', $value));
    }

    public function setMaterialAttribute($value)
    {
        $this->attributes['material'] =  str_replace(',', '.', str_replace(array('R$',' ','.'), '', $value));
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function procedureGroup()
    {
        return $this->belongsTo(ProcedureGroup::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function occupations()
    {
        return $this->belongsToMany(Occupation::class);
    }

    public function discountTables()
    {
        return $this->belongsToMany(DiscountTable::class);
    }
}
