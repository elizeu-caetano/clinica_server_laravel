<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'street' =>  $this->street ?? null,
            'number' =>  $this->number ?? null,
            'details' =>  $this->details ?? null,
            'zip' => $this->zip ?? null,
            'district' =>  $this->district ?? null,
            'city' =>  $this->city ?? null,
            'state' =>  $this->state ?? null,
            'country' =>  $this->country ?? null
        ];
    }
}
