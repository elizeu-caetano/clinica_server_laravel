<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class DiscountTableProcedure extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['discount_table_id', 'procedure_id', 'price'];

    public function generateTags(): array
    {
        return [
            'Tabela de Descontos dos Procedimentos'
        ];
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function discountTable()
    {
        return $this->belongsTo(DiscountTable::class);
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
