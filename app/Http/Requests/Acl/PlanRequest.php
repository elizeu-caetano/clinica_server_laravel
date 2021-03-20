<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{    
    
    public function rules()
    {       
        $rules = [
            'name' => ['required', 'min:6', 'unique:plans'],
            'price' => ['required', 'numeric']
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
            'name.unique' => 'Já existe um plano com este nome.',
            'price.required' => 'Digite um Valor.',
            'price.numeric' => 'O Valor deve conter apenas números'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
