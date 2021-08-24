<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUpdateProcedureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:5',
                'max:100',
                Rule::unique('procedures')->where(function ($query) {
                    return $query->where('id', '!=', $this->id)->Where('contractor_id', Auth::user()->id);
                })
            ],
            'price' => ['required'],
            'commission' => ['required'],
            'material' => ['required'],
            'procedure_group_id' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O Nome é obrigatório.',
            'name.min' => 'O Nome não pode ser menor que 5 caracteres.',
            'name.max' => 'O Nome não pode ser maior que 100 caracteres.',
            'name.unique' => 'Este Procedimento já foi cadastrado.',
            'price.required' => 'O Preço é obrigatório.',
            'commission.required' => 'A Comissão é obrigatória.',
            'material.required' => 'O Material é obrigatório.',
            'procedure_group_id.required' => 'Selecione um grupo de procediemnto.',
        ];
    }

    /**
     * @overrride
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
