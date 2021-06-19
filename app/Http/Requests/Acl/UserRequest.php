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
            'cell.required' => 'O Celular é obrigatório.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
