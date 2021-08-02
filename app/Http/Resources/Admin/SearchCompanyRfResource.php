<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SearchCompanyRfResource extends JsonResource
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
            'name' => $this['name'] . ' - ' . $this['size'],
            'fantasy_name' => Str::title($this['alias']),
            'cpf_cnpj' => $this->maskCnpj($this['tax_id']),
            'phone' => $this['phone'],
            'email' => $this['email'],
            'state_registration' => $this['sintegra']['home_state_registration'] ?? 'ISENTO',
            'address' => new AddressRfResource($this['address'])
        ];
    }

    private function maskCnpj($cnpj)
    {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' .
                substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
}
