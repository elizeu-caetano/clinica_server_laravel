<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImageRequest extends FormRequest
{

    public function rules()
    {
        return [
            'image' => ['required', 'image']
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Selecione uma imagem.',
            'image.image' => 'Selecione uma imagem válida do tipo (jpg, jpeg, png, bmp, gif, svg, ou webp)',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors]));
    }
}
