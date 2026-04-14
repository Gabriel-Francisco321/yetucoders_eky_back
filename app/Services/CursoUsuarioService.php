<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\Usuario;

class CursoUsuarioService
{
    public function inscrever(int $usuarioId, int $cursoId): void
    {
        $usuario = Usuario::findOrFail($usuarioId);

        if ($usuario->cursos()->where('curso_id', $cursoId)->exists()) {
            throw new \Exception('Usuário já inscrito neste curso.', 409);
        }

        $usuario->cursos()->attach($cursoId);
    }

    public function cancelar(int $usuarioId, int $cursoId): void
    {
        $usuario = Usuario::findOrFail($usuarioId);

        $usuario->cursos()->detach($cursoId);
    }

    public function cursosDoUsuario(int $usuarioId)
    {
        $usuario = Usuario::with('cursos')->find($usuarioId);

        if (!$usuario) {
            throw new \Exception('Usuário não encontrado.', 404);
        }

        return $usuario->cursos;
    }

    public function usuariosDoCurso(int $cursoId)
    {
        $curso = Curso::with('usuarios')->find($cursoId);

        if (!$curso) {
            throw new \Exception('Curso não encontrado.', 404);
        }

        return $curso->usuarios;
    }
}
