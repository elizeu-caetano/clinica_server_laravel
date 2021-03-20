<?php

namespace App\Http\Resources\Acl;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'permission' => $this->permission,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
