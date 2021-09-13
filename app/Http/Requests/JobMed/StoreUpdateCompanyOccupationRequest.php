<?php

namespace App\Http\Requests\JobMed;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateCompanyOccupationRequest extends FormRequest
{

    public function rules()
    {
        $roles = [
            'company_id' => ['required'],
            'occupation_id' => ['required']
        ];

        return $roles;

    }

    public function messages()
    {
        return [
            'company_id.required' => 'Selecione uma Empresa.',
            'occupation_id.required' => 'Selecione uma Função.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
