<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstrutorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario' => ['required', 'integer', 'exists:usuarios,id', 'unique:instrutores,id_usuario'],
            'biografia'  => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }
}
