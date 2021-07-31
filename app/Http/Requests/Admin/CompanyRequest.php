<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{

    public function rules()
    {
        $this->cpf_cnpj = $this->type_person == 'F' ? 'cpf' : 'cnpj';

        $roles = [
            'name' => ['required','min:6'],
            'fantasy_name' => ['required'],
            'type_person' => ['required', Rule::in(['F', 'J'])],
            'cpf_cnpj' => [Rule::unique('companies')->where(function ($query) {
                            return $query->where('id', '!=', $this->id)->Where('contractor_id', Auth::user()->id);
                            }), 'required', "{$this->cpf_cnpj}"],
            'closing_day' => ['required'],
            'pay_day' => ['required'],
            'phone' => ['required', 'telefone_com_ddd'],
            'phone_cell' => ['required', 'celular_com_ddd'],
            'email_main' => ['required', 'email']
        ];

        return $roles;

    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.min' => 'O Nome não pode ser menor que 6 caracteres.',
            'fantasy_name.required' => 'O Nome Fantasia é obrigatório.',
            "cpf_cnpj.{$this->cpf_cnpj}" => 'O ' .  Str::upper($this->cpf_cnpj) . ' é inválido.',
            "cpf_cnpj.required" => 'O ' .  Str::upper($this->cpf_cnpj) . ' é Obrigatório.',
            "cpf_cnpj.unique" => 'Este ' . Str::upper($this->cpf_cnpj) . ' já foi cadastrado.',
            "type_person.required" => 'Selecione um tipo de pessoa.',
            "type_person.in" => 'Tipo de pessoa inválido.',
            'closing_day.required' => 'O dia do fechamento é obrigatório.',
            'pay_day.required' => 'O dia do pagamento é obrigatório.',
            'phone.required' => 'O Telefone é obrigatório.',
            'phone.telefone_com_ddd' => 'O Telefone é inválido.',
            'phone_cell.required' => 'O Celular é obrigatório.',
            'phone_cell.celular_com_ddd' => 'O Celular é inválido.',
            'email_main.required' => 'O Email é obrigatório.',
            'email_main.email' => 'O Email é inválido.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
