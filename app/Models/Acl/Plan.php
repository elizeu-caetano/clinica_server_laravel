<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
   protected $fillable = ['uuid', 'name', 'price', 'active'];

   public function contractors()
   {
      return $this->belongsToMany(Contractor::class);
   }

   public function permissions()
   {
      return $this->belongsToMany(Permission::class);
   }
}
