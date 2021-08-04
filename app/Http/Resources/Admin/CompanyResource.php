<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CompanyResource extends JsonResource
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
            'fantasy_name' => Str::title($this->fantasy_name),
            'cpf_cnpj' => $this->cpf_cnpj,
            'state_registration' => $this->state_registration,
            'phone' => $this->phones()->where('type', 'Fixo')->first()->phone ?? null,
            'phone_cell' => $this->phones()->where('type', 'Celular')->first()->phone ?? null,
            'email' => $this->emails()->where('type', 'Principal')->first()->email ?? null,
            'type_person' => $this->type_person,
            'person' => $this->type_person == 'J' ? 'Jurídica' : 'Física',
            'closing_day' => $this->closing_day,
            'pay_day' => $this->pay_day,
            'active' => $this->active ? 'Sim' : 'Não',
            'address' => new AddressResource($this->addresses()->where('type', 'Residêncial')->first()) ?? null,
            'logo' => $this->logo ? env('AWS_URL').$this->logo : null,
            'deleted' => $this->deleted ? 'Sim' : 'Não',
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y H:i:s')
        ];
    }
}
