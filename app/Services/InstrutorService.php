<?php

namespace App\Services;

use App\Models\Instrutor;
use Illuminate\Pagination\LengthAwarePaginator;

class InstrutorService
{
    public function listar(): LengthAwarePaginator
    {
        return Instrutor::with('usuario')
            ->latest()
            ->paginate(10);
    }

    public function buscar(int $id): Instrutor
    {
        $instrutor = Instrutor::with('usuario')->find($id);

        if (! $instrutor) {
            throw new \Exception('Instrutor não encontrado.', 404);
        }

        return $instrutor;
    }

    public function criar(array $dados): Instrutor
    {
        $instrutor = Instrutor::create([
            'id_usuario' => $dados['id_usuario'],
            'biografia'  => $dados['biografia'],
        ]);

        return $instrutor->load('usuario');
    }

    public function actualizar(int $id, array $dados): Instrutor
    {
        $instrutor = $this->buscar($id);
        $instrutor->update(['biografia' => $dados['biografia']]);
        return $instrutor->fresh('usuario');
    }

    public function eliminar(int $id): void
    {
        $instrutor = $this->buscar($id);
        $instrutor->delete();
    }

    public function listarEliminados(): LengthAwarePaginator
    {
        return Instrutor::onlyTrashed()->with('usuario')->latest()->paginate(10);
    }

    public function restaurar(int $id): Instrutor
    {
        $instrutor = Instrutor::withTrashed()->find($id);

        if (!$instrutor) {
            throw new \Exception('Instrutor não encontrado.', 404);
        }

        $instrutor->restore();

        return $instrutor->load('usuario');
    }

    public function eliminarPermanente(int $id): void
    {
        $instrutor = Instrutor::withTrashed()->find($id);

        if (!$instrutor) {
            throw new \Exception('Instrutor não encontrado.', 404);
        }

        $instrutor->forceDelete();
    }
}
