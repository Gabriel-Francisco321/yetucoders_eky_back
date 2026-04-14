<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvaliacaoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nota' => $this->nota,
            'comentario' => $this->comentario,
            'id_usuario' => $this->id_usuario,
            'id_curso' => $this->id_curso,
            'curso' => new CursoResource($this->whenLoaded('curso')),
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
