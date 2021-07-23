<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'type', 'deleted', 'emailable_type', 'emailable_id'];

    public function emailable()
    {
        return $this->morphTo();
    }
}
