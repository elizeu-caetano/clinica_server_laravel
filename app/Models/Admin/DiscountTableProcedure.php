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

    protected $fillable = ['discount_table_id', 'procedure_id', 'price', 'is_percentage'];

    protected $table = 'discount_table_procedure';

    public function generateTags(): array
    {
        return [
            'Tabela de Descontos dos Procedimentos'
        ];
    }

    public function discountTables()
    {
        return $this->belongsToMany(DiscountTable::class);
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
