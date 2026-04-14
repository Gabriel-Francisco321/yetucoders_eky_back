<?php

namespace App\Services;

use App\Models\Avaliacao;
use Illuminate\Pagination\LengthAwarePaginator;

class AvaliacaoService
{
    public function listarPorUsuario(int $idUsuario): LengthAwarePaginator
    {
        return Avaliacao::where('id_usuario', $idUsuario)
            ->with('curso')
            ->latest()
            ->paginate(15);
    }

    public function listarPorCurso(int $idCurso)
    {
        $avaliacoes = Avaliacao::where('id_curso', $idCurso)->get();
        $classificacao = Avaliacao::where('id_curso', $idCurso)->avg('nota');

        return [
            'avaliacoes' => $avaliacoes,
            'classificacao' => $classificacao,
        ];
    }

    public function criar(array $dados, int $idUsuario): Avaliacao
    {
        return Avaliacao::create([
            'nota' => $dados['nota'],
            'comentario' => $dados['comentario'] ?? null,
            'id_usuario' => $idUsuario,
            'id_curso' => $dados['id_curso'],
        ]);
    }

    public function eliminar(int $id, int $idUsuario): void
    {
        $avaliacao = Avaliacao::where('id', $id)
            ->where('id_usuario', $idUsuario)
            ->first();

        if (!$avaliacao) {
            throw new \Exception('Avaliação não encontrada.', 404);
        }

        $avaliacao->delete();
    }
}
