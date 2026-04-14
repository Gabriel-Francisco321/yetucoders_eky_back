<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CursoUsuarioController extends Controller
{
    public function inscrever(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $usuario = Usuario::find($validated['usuario_id']);

        if ($usuario->cursos()->where('curso_id', $validated['curso_id'])->exists()) {
            return response()->json(['message' => 'Usu\u00e1rio j\u00e1 inscrito neste curso'], 409);
        }

        $usuario->cursos()->attach($validated['curso_id']);

        return response()->json(['message' => 'Inscri\u00e7\u00e3o realizada com sucesso'], 201);
    }

    public function cancelar(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $usuario = Usuario::find($validated['usuario_id']);

        $usuario->cursos()->detach($validated['curso_id']);

        return response()->json(['message' => 'Inscri\u00e7\u00e3o cancelada com sucesso'], 200);
    }

    public function cursosDoUsuario($usuario_id)
    {
        $usuario = Usuario::with('cursos')->find($usuario_id);

        if (!$usuario) {
            return response()->json(['message' => 'Usu\u00e1rio n\u00e3o encontrado'], 404);
        }

        return response()->json($usuario->cursos, 200);
    }

    public function usuariosDoCurso($curso_id)
    {
        $curso = Curso::with('usuarios')->find($curso_id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        return response()->json($curso->usuarios, 200);
    }
}
