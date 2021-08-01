<?php

namespace App\Http\Resources\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class CepResource extends JsonResource
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
        'street' =>  $this['logradouro'] ?? null,
        'zip' => $this['cep'] ?? null,
        'district' =>  $this['bairro'] ?? null,
        'city' =>  $this['localidade'] ?? null,
        'state' =>  $this['uf'] ?? null,
        'city_ibge' =>  $this['ibge'] ?? null
       ];
    }
}
