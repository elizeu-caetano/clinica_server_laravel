<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'type', 'deleted', 'is_whatsapp', 'phoneable_type', 'phoneable_id'];

    public function phoneable()
    {
        return $this->morphTo();
    }
}
