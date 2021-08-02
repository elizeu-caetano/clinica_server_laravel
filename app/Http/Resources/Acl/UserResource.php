<?php

namespace App\Http\Resources\Acl;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'photo' =>  $this->photo ? env('AWS_URL').$this->photo : null,
            'email' => $this->email,
            'contractor' => new ContractorResource($this->contractor),
            'active' => $this->active ? 'Sim' : 'Não',
            'deleted' => $this->deleted ? 'Sim' : 'Não',
            'cell' => $this->phones()->where('type', 'Celular')->first()->phone ?? null,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s'),
        ];
    }
}
