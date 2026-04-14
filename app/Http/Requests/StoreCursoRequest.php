<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string', 'max:500'],
            'objectivos' => ['required', 'string', 'max:300'],
            'requisitos' => ['nullable', 'string', 'max:300'],
            'preco' => ['nullable', 'decimal:0,2'],
            'nivel' => ['required', 'in:Iniciante,Intermediário,Avançado'],
            'id_instrutor' => ['required', 'integer', 'exists:instrutores,id'],
            'id_categoria' => ['required', 'integer', 'exists:categorias,id'],
        ];
    }
}
