<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcedureResource extends JsonResource
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
            'commission_value' => $this->formatMaterial(),
            'commission' => $this->commission,
            'material' => 'R$ ' . number_format($this->material, 2, ',', '.'),
            'is_percentage' => $this->is_percentage ? true : false,
            'active' => $this->active,
            'deleted' => $this->deleted,
            'is_print' => $this->is_print,
            'is_print' => $this->is_print ? true : false,
            'procedure_group_id' => $this->procedureGroup->id,
            'procedure_group' => $this->procedureGroup->name,
            'contractor_id' => $this->contractor->id,
            'contractor' => $this->contractor->name,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s') ?? null
        ];
    }

    private function formatMaterial()
    {
        if ($this->is_percentage || null) {
            $value = ($this->price * $this->commission) / 100;
            $valueExtenso = 'R$ ' . number_format($value, 2, ',', '.');
            $comission = number_format($this->commission, 2, ',', '.');
            return $valueExtenso . ' = ' . $comission . '%';
        } else {
            return 'R$ ' . number_format($this->commission, 2, ',', '.');
        }
    }
}
