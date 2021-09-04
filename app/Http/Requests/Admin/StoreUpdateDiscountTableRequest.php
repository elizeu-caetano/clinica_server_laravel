<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUpdateDiscountTableRequest extends FormRequest
{

    public function rules()
    {
        $this->cpf_cnpj = $this->type_person == 'F' ? 'cpf' : 'cnpj';

        $roles = [
            'name' => [
                        Rule::unique('discount_tables')->where(function ($query) {
                            return $query->where('id', '!=', $this->id)->Where('contractor_id', Auth::user()->id);
                            }),
                        'required'
                    ]
        ];

        return $roles;

    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            "name.unique" => 'Este nome já foi cadastrado.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
