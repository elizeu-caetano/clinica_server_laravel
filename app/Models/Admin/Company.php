<?php

namespace App\Models\Admin;

use App\Models\Acl\Contractor;
use App\Models\Acl\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Company extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = ['uuid', 'name', 'fantasy_name', 'cpf_cnpj', 'state_registration', 'type_person', 'active', 'deleted',
                            'closing_day', 'pay_day', 'logo', 'contractor_id'];

    public function generateTags(): array
    {
        return [
            'Empresas'
        ];
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'emailable');
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
