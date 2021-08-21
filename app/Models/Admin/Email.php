<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Email extends Model implements AuditableContract
{
    use HasFactory, Auditable, AuditTrait;

    protected $fillable = ['email', 'type', 'deleted', 'emailable_type', 'emailable_id'];

    public function generateTags(): array
    {
        return [
            'emails'
        ];
    }

    public function emailable()
    {
        return $this->morphTo();
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
