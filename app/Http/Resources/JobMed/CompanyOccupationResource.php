<?php

namespace App\Http\Resources\JobMed;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyOccupationResource extends JsonResource
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
            'cbo' => substr($this->occupation->code, 0, -2) . '-' . substr($this->occupation->code, -2),
            'name' => $this->occupation->name,
            'type' => $this->occupation->type,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
