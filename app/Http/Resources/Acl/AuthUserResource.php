<?php

namespace App\Http\Resources\Acl;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'users' => explode(' ', $this->name)[0],
            'contractor' => $this->contractor->fantasy_name,
            'token' => $this->token,
            'logo' => $this->contractor->logo ? Storage::path(env('AWS_URL').$this->contractor->logo) : null,  
            'photo' => $this->photo ? Storage::path(env('AWS_URL').$this->photo) : null,
            'permissions' => $this->permissions,
            'admin' => $this->admin
        ];
    }
}
