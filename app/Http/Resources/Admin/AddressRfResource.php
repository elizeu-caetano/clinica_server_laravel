<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressRfResource extends JsonResource
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
            'street' =>  $this['street'] ?? null,
            'number' =>  $this['number'] ?? null,
            'details' =>  $this['details'] ?? null,
            'zip' => substr($this['zip'], 0, 5). '-' .substr($this['zip'], 5, 3) ?? null,
            'district' =>  $this['neighborhood'] ?? null,
            'city' =>  $this['city'] ?? null,
            'state' =>  $this['state'] ?? null,
            'city_ibge' =>  $this['city_ibge'] ?? null,
            'state_ibge' =>  $this['state_ibge'] ?? null
        ];
    }
}
