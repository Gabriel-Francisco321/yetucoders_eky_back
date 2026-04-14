<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function listar(): LengthAwarePaginator
    {
        return Usuario::latest()->paginate(15);
    }

    public function buscar(int $id): Usuario
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            throw new \Exception('Usuário não encontrado.', 404);
        }

        return $usuario;
    }

    public function criar(array $dados): Usuario
    {
        $dados['senha'] = Hash::make($dados['senha']);

        return Usuario::create($dados);
    }

    public function actualizar(int $id, array $dados): Usuario
    {
        $usuario = $this->buscar($id);

        if (isset($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        }

        $usuario->update($dados);

        return $usuario->fresh();
    }

    public function eliminar(int $id): void
    {
        $usuario = $this->buscar($id);
        $usuario->delete();
    }

    public function listarEliminados(): LengthAwarePaginator
    {
        return Usuario::onlyTrashed()->latest()->paginate(15);
    }

    public function restaurar(int $id): Usuario
    {
        $usuario = Usuario::withTrashed()->find($id);

        if (!$usuario) {
            throw new \Exception('Usuário não encontrado.', 404);
        }

        $usuario->restore();

        return $usuario;
    }

    public function eliminarPermanente(int $id): void
    {
        $usuario = Usuario::withTrashed()->find($id);

        if (!$usuario) {
            throw new \Exception('Usuário não encontrado.', 404);
        }

        $usuario->forceDelete();
    }
}
