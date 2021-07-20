<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatePasswordRequest extends FormRequest
{

    public function rules()
    {
        $roles = [
            'old_password' => ['required', 'min:6', 'current_password:api'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];

        return $roles;
    }

    public function messages()
    {
        return [
            'old_password.required' => 'A Senha antiga é obrigatória.',
            'old_password.min' => 'A Senha antiga tem que ter no mínimo 6 caracteres.',
            'old_password.current_password' => 'A Senha antiga não corresponde com a senha cadastrada.',
            'password.required' => 'A Nova Senha é obrigatória.',
            'password.min' => 'A Nova Senha tem que ter no mínimo 6 caracteres.',
            'password_confirmation.required' => 'A Senha de confirmação é obrigatória.',
            'password.confirmed' => 'A Senha de confirmação não corresponde com a nova senha.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
