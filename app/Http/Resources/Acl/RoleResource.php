<?php

namespace App\Http\Resources\Acl;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'admin' => $this->admin ? 'Sim' : 'Não',
            'active' => $this->active ? 'Sim' : 'Não',
            'deleted' => $this->deleted ? 'Sim' : 'Não',
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
