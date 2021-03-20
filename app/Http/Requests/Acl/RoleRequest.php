<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{    
    
    public function rules()
    {       
        $rules = [
            'name' => [
                'required', 'min:6', 
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('contractor_id', Auth::user()->contractor->id);
                })
            ],
        ];

        if ($this->method() == 'PUT') {
            $rules = [
                'name' => ['required', 'min:6']
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.min' => 'O Nome não pode ser menor que 6 caracteres.',
            'name.unique' => 'Já existe uma função com este nome.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }

}
