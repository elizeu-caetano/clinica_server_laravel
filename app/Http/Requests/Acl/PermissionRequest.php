<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionRequest extends FormRequest
{    
    
    public function rules()
    {       
        $rules = [
            'name' => ['required', 'unique:permissions'],
            'permission' => ['required', 'unique:permissions']
        ];

        if ($this->method() == 'PUT') {
            $rules = [
                'name' => ['required'],
                'permission' => ['required']
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.unique' => 'Já existe uma permissão com este nome.',
            'permission.required' => 'A Permissão é obrigatória.',
            'permission.unique' => 'Já existe uma permissão com esta permission.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }


}
