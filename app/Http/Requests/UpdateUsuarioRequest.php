<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('usuario');

        return [
            'nome' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', Rule::unique('usuarios')->ignore($id)],
            'senha' => ['sometimes', 'string', 'min:6'],
            'tipo' => ['sometimes', 'in:aluno,instrutor'],
        ];
    }
}
