<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstrutorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'biografia' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'biografia.required' => 'A biografia é obrigatória.',
            'biografia.min'      => 'A biografia deve ter pelo menos 20 caracteres.',
            'biografia.max'      => 'A biografia não pode exceder 2000 caracteres.',
        ];
    }
}
