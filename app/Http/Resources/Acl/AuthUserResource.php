<?php

namespace App\Http\Resources\Acl;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'name' =>  Str::title($this->name),
            'first_name' =>  Str::title(explode(' ', $this->name)[0]),
            'contractor' => Str::title($this->contractor->fantasy_name),
            'token' => $this->token,
            'logo' => $this->contractor->logo ? env('AWS_URL').$this->contractor->logo : null,
            'photo' => $this->photo ? env('AWS_URL').$this->photo : null,
            'permissions' => $this->permissions,
            'admin' => $this->admin
        ];
    }
}
