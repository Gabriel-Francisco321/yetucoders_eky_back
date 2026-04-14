<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Pagination\LengthAwarePaginator;

class CursoService
{
    public function listar(): LengthAwarePaginator
    {
        return Curso::with(['instrutor', 'categoria'])
            ->latest()
            ->paginate(15);
    }

    public function buscar(int $id): Curso
    {
        $curso = Curso::with(['instrutor', 'categoria', 'aulas'])->find($id);

        if (!$curso) {
            throw new \Exception('Curso não encontrado.', 404);
        }

        return $curso;
    }

    public function criar(array $dados): Curso
    {
        $curso = Curso::create($dados);

        return $curso->load(['instrutor', 'categoria']);
    }

    public function actualizar(int $id, array $dados): Curso
    {
        $curso = $this->buscar($id);
        $curso->update($dados);

        return $curso->fresh(['instrutor', 'categoria']);
    }

    public function eliminar(int $id): void
    {
        $curso = $this->buscar($id);
        $curso->delete();
    }

    public function listarEliminados(): LengthAwarePaginator
    {
        return Curso::onlyTrashed()->latest()->paginate(15);
    }

    public function restaurar(int $id): Curso
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            throw new \Exception('Curso não encontrado.', 404);
        }

        $curso->restore();

        return $curso;
    }

    public function eliminarPermanente(int $id): void
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            throw new \Exception('Curso não encontrado.', 404);
        }

        $curso->forceDelete();
    }
}
