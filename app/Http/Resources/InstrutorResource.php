<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrutorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'biografia' => $this->biografia,
            'usuario' => [
                'id' => $this->usuario->id,
                'nome' => $this->usuario->nome,
                'email' => $this->usuario->email,
            ],
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),
            'actualizado_em' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
