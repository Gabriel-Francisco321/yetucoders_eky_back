<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CursoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'objectivos' => $this->objectivos,
            'requisitos' => $this->requisitos,
            'preco' => $this->preco,
            'nivel' => $this->nivel,
            'instrutor' => new InstrutorResource($this->whenLoaded('instrutor')),
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'aulas' => AulaResource::collection($this->whenLoaded('aulas')),
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),
            'actualizado_em' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
