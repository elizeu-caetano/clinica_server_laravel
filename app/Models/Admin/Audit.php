<?php

namespace App\Models\Admin;

use App\Models\Acl\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = ['user_type', 'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values',
                            'url', 'ip_address', 'user_agent', 'tags'];

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
