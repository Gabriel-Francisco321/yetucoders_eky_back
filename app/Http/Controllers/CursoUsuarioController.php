<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CursoUsuarioController
{
    /**
     * Inscrever usuário em um curso
     */
    public function inscrever(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $usuario = Usuario::find($validated['usuario_id']);

        // evita duplicação
        if ($usuario->cursos()->where('curso_id', $validated['curso_id'])->exists()) {
            return response()->json(['message' => 'Usuário já inscrito neste curso'], 409);
        }

        $usuario->cursos()->attach($validated['curso_id']);

        return response()->json(['message' => 'Inscrição realizada com sucesso'], 201);
    }

    /**
     * Cancelar inscrição 
     */
    public function cancelar(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $usuario = Usuario::find($validated['usuario_id']);

        $usuario->cursos()->detach($validated['curso_id']);

        return response()->json(['message' => 'Inscrição cancelada com sucesso'], 200);
    }

    /**
     * Listar cursos de um usuário específico 
     */
    public function cursosDoUsuario($usuario_id)
    {
        $usuario = Usuario::with('cursos')->find($usuario_id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json($usuario->cursos, 200);
    }

    /**
     * Listar usuários de um curso específico 
     */
    public function usuariosDoCurso($curso_id)
    {
        $curso = Curso::with('usuarios')->find($curso_id);

        if (!$curso) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        return response()->json($curso->usuarios, 200);
    }
}
