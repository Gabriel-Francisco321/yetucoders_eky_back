<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nota' => ['required', 'integer', 'min:0', 'max:100'],
            'comentario' => ['nullable', 'string'],
            'id_curso' => ['required', 'integer', 'exists:cursos,id'],
        ];
    }
}
