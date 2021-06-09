<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthUserRequest extends FormRequest
{

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'digits_between:6,12'],
            'device_name' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'device_name.required' => 'O Nome do aplicativo é obrigatório.',
            'email.required' => 'O Email é obrigatório.',
            'email.email' => 'O Email é inválido.',
            'password.required' => 'Digite uma Senha.',
            'password.digits_between' => ' A Senha deve ter no mínimo 6 e no máximo 12 caracteres.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }


}
