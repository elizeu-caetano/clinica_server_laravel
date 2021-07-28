<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Phone extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['phone', 'type', 'deleted', 'is_whatsapp', 'phoneable_type', 'phoneable_id'];

    public function generateTags(): array
    {
        return [
            'Telefones'
        ];
    }

    public function phoneable()
    {
        return $this->morphTo();
    }
}
