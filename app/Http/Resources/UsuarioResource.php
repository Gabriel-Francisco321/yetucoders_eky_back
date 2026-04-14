<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'tipo' => $this->tipo,
            'criado_em' => $this->created_at?->format('d/m/Y H:i'),
            'actualizado_em' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
