<?php

namespace App\Http\Requests\JobMed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateOccupationRequest extends FormRequest
{

    public function rules()
    {
        $roles = [
            'code' => ['required'],
            'name' => ['required', 'unique:occupations'],
            'type' => ['required']
        ];

        return $roles;

    }

    public function messages()
    {
        return [
            'code.required' => 'O Código é obrigatório.',
            'name.required' => 'O Nome é obrigatório.',
            "name.unique" => 'Este nome já foi cadastrado.',
            "type.required" => 'O Tipo é obrigatório.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
