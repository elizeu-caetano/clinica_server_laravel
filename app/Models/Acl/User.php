<?php

namespace App\Models\Acl;

use App\Models\Admin\Audit;
use App\Traits\UserAclTrait;
use App\Models\Admin\Phone;
use App\Traits\AuditTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, Notifiable, UserAclTrait, Auditable, AuditTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'name', 'email', 'password', 'contractor_id', 'token', 'photo'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateTags(): array
    {
        return [
            $this->uuid ?? $this->id
        ];
    }


    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
