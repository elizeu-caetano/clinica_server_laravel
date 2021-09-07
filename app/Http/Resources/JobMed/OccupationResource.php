<?php

namespace App\Http\Resources\JobMed;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupationResource extends JsonResource
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
            'code' => $this->code,
            'cbo' => substr($this->code, 0, -2) . '-' . substr($this->code, -2),
            'name' => $this->name,
            'type' => $this->type,
            'active' => $this->active,
            'deleted' => $this->deleted,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
