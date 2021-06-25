<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContractorRequest extends FormRequest
{

    public function rules()
    {
        $this->cpf_cnpj = $this->type_person == 'F' ? 'cpf' : 'cnpj';

        $roles = [
            'name' => ['required','min:6'],
            'fantasy_name' => ['required'],
            'phone' => ['required', 'celular_com_ddd'],
            'email' => ['required', 'email'],
            'fantasy_name' => ['required'],
            'type_person' => ['required', Rule::in(['F', 'J'])],
            "cpf_cnpj" => [Rule::unique('contractors')->ignore($this->id), 'required', "{$this->cpf_cnpj}"],
        ];

        if ($this->method() == 'PUT') {
            $roles = [
               //'cpf_cnpj' => ['required', "{$this->cpf_cnpj}"]
            ];
        }

        return $roles;

    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.min' => 'O Nome não pode ser menor que 6 caracteres.',
            'fantasy_name.required' => 'O Nome Fantasia é obrigatório.',
            'phone.required' => 'O Telefone é obrigatório.',
            'phone.celular_com_ddd' => 'O Telefone é inválido.',
            'email.required' => 'O Email é obrigatório.',
            'email.email' => 'O Email é inválido.',
            "cpf_cnpj.{$this->cpf_cnpj}" => 'O ' .  Str::upper($this->cpf_cnpj) . ' é inválido.',
            "cpf_cnpj.required" => 'O ' .  Str::upper($this->cpf_cnpj) . ' é Obrigatório.',
            "cpf_cnpj.unique" => 'Este ' . Str::upper($this->cpf_cnpj) . ' já foi cadastrado.',
            // "logo.image" => 'Selecione uma imágem válida.',
            "type_person.required" => 'Selecione um tipo de pessoa.',
            "type_person.in" => 'Tipo de pessoa inválido.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
