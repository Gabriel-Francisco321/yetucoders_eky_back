<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AulaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_curso' => $this->id_curso,
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'conteudo_url' => $this->conteudo_url,
            'duracao' => $this->duracao,
            'ordem' => $this->ordem,
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),
            'actualizado_em' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
