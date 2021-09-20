<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountTableProceduresResource extends JsonResource
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
            'id' =>  $this->id,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s'),
            'name' => $this->procedure->name,
            'price' => 'R$ ' . number_format($this->procedure->price, 2, ',', '.'),
            'price_discount' =>  $this->price,
            'procedure_id' =>  $this->procedure_id,
            'discount_table_id' =>  $this->discount_table_id,
            'procedure_group' => $this->procedure->procedureGroup->name
        ];
    }
}
