<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function rules()
    {
        $roles = [
            'name' => ['required', 'min:6'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id)],
            'password' => [Rule::requiredIf($this->password), 'digits_between:6,12'],
            'cell' => 'required'
        ];

        if ($this->method() == 'PUT') {
            $roles = [
                'email' => ['required', 'email']
            ];
        }

        return $roles;
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.min' => 'O Nome não pode ser menor que 6 caracteres.',
            'email.required' => 'O Email é obrigatório.',
            'email.unique' => 'Já existe um usuário com este e-mail.',
            'email.email' => 'O Email é inválidu.',
            'password.required' => 'Digite uma Senha.',
            'password.digits_between' => ' A Senha deve ter no mínimo 6 e no máximo 12 caracteres.',
            'cell.required' => 'O Celular é obrigatório.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
