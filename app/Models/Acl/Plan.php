<?php

namespace App\Models\Acl;

use App\Models\Admin\Audit;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use App\Traits\AuditTrait;

class Plan extends Model implements AuditableContract
{
    use Auditable, AuditTrait;

    protected $fillable = ['uuid', 'name', 'price', 'active'];

    public function generateTags(): array
    {
        return [
            $this->uuid ?? $this->id
        ];
    }

    public function contractors()
    {
        return $this->belongsToMany(Contractor::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  str_replace(',', '.', str_replace(array('R$',' ','.'), '', $value));
    }

    public function audit()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
