<?php

namespace App\Http\Resources\Acl;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContractorResource extends JsonResource
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
            'fantasy_name' => Str::title($this->fantasy_name),
            'cpf_cnpj' => $this->cpf_cnpj,
            'phone' => $this->phone,
            'type_person' => $this->type_person,
            'plan_id' => DB::table('contractor_plan as cp')->join('plans as p', 'p.id', '=', 'cp.plan_id')
                        ->select('p.id as plan_id', 'p.name')->where('cp.contractor_id', $this->id)
                        ->where('p.id', '>', 2)->get(),
            'person' => $this->type_person == 'J' ? 'Jurídica' : 'Física',
            'active' => $this->active ? 'Sim' : 'Não',
            'logo' => $this->logo ? env('AWS_URL').$this->logo : null,
            'deleted' => $this->deleted ? 'Sim' : 'Não',
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
