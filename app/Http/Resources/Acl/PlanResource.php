<?php

namespace App\Http\Resources\Acl;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'price' => 'R$ ' . number_format($this->price, 2, ',', '.'),
            'active' => $this->active ? 'Sim' : 'Não',
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
