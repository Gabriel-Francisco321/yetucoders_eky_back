<?php

namespace App\Services;

use App\Models\Categoria;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoriaService
{
    public function listar(): LengthAwarePaginator
    {
        return Categoria::latest()->paginate(15);
    }

    public function buscar(int $id): Categoria
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            throw new \Exception('Categoria não encontrada.', 404);
        }

        return $categoria;
    }

    public function criar(array $dados): Categoria
    {
        return Categoria::create($dados);
    }

    public function actualizar(int $id, array $dados): Categoria
    {
        $categoria = $this->buscar($id);
        $categoria->update($dados);

        return $categoria->fresh();
    }

    public function eliminar(int $id): void
    {
        $categoria = $this->buscar($id);
        $categoria->delete();
    }

    public function listarEliminados(): LengthAwarePaginator
    {
        return Categoria::onlyTrashed()->latest()->paginate(15);
    }

    public function restaurar(int $id): Categoria
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (!$categoria) {
            throw new \Exception('Categoria não encontrada.', 404);
        }

        $categoria->restore();

        return $categoria;
    }

    public function eliminarPermanente(int $id): void
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (!$categoria) {
            throw new \Exception('Categoria não encontrada.', 404);
        }

        $categoria->forceDelete();
    }
}
