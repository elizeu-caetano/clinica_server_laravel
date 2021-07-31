<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'street', 'number', 'details', 'zip', 'district', 'city', 'state', 'country', 'city_ibge',
                            'state_ibge', 'deleted', 'emailble_type', 'emailble_id'];

    public function addressable()
    {
        return $this->morphTo();
    }
}
