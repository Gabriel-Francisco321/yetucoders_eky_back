<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAulaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_curso' => ['required', 'integer', 'min:1'],
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:video,texto,pdf'],
            'conteudo_url' => ['required', 'url', 'max:2048'],
            'duracao' => ['required', 'integer', 'min:1'],
            'ordem' => ['required', 'integer', 'min:1'],
        ];
    }
}
