<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Address extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['type', 'street', 'number', 'details', 'zip', 'district', 'city', 'state', 'country', 'city_ibge',
                            'state_ibge', 'deleted', 'emailble_type', 'emailble_id'];

    public function generateTags(): array
    {
        return [
            'addresses'
        ];
    }

    public function addressable()
    {
        return $this->morphTo();
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
