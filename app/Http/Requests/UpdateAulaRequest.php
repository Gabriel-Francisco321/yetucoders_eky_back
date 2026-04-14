<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAulaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $aulaId = $this->route('aula');

        return [
            'id_curso' => ['required', 'integer', 'exists:cursos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:video,texto,pdf'],
            'conteudo_url' => ['required', 'url', 'max:2048'],
            'duracao' => ['required', 'integer', 'min:1'],
            'ordem' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('aulas')
                    ->where(fn ($q) => $q->where('id_curso', $this->input('id_curso')))
                    ->ignore($aulaId),
            ],
        ];
    }
}
