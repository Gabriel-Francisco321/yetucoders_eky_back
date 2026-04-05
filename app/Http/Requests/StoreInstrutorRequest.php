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
            'id_usuario' => ['required', 'integer', 'exists:users,id', 'unique:instrutores,id_usuario'],
            'biografia'  => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'O utilizador é obrigatório.',
            'id_usuario.exists'   => 'Utilizador não encontrado.',
            'id_usuario.unique'   => 'Este utilizador já é instrutor.',
            'biografia.required'  => 'A biografia é obrigatória.',
            'biografia.min'       => 'A biografia deve ter pelo menos 20 caracteres.',
            'biografia.max'       => 'A biografia não pode exceder 2000 caracteres.',
        ];
    }
}
