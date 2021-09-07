<?php

namespace App\Http\Requests\JobMed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateDangerRequest extends FormRequest
{

    public function rules()
    {
        $roles = [
            'name' => ['required', 'unique:occupations']
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
