<?php

namespace App\Services;

use App\Models\Aula;
use Illuminate\Pagination\LengthAwarePaginator;

class AulaService
{
    public function listar(): LengthAwarePaginator
    {
        return Aula::orderBy('id_curso')
            ->orderBy('ordem')
            ->orderBy('id')
            ->paginate(10);
    }

    public function listarPorCurso(int $idCurso): LengthAwarePaginator
    {
        return Aula::where('id_curso', $idCurso)
            ->orderBy('ordem')
            ->orderBy('id')
            ->paginate(10);
    }

    public function buscar(int $id): Aula
    {
        $aula = Aula::find($id);

        if (! $aula) {
            throw new \Exception('Aula não encontrada.', 404);
        }

        return $aula;
    }

    public function criar(array $dados): Aula
    {
        return Aula::create($dados);
    }

    public function actualizar(int $id, array $dados): Aula
    {
        $aula = $this->buscar($id);
        $aula->update($dados);

        return $aula->fresh();
    }

    public function eliminar(int $id): void
    {
        $aula = $this->buscar($id);
        $aula->delete();
    }
}
