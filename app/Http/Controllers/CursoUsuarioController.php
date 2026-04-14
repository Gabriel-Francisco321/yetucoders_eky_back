<?php

namespace App\Http\Controllers;

use App\Services\CursoUsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CursoUsuarioController extends Controller
{
    public function __construct(
        private CursoUsuarioService $service
    ) {}

    public function inscrever(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        try {
            $this->service->inscrever($validated['usuario_id'], $validated['curso_id']);
            return response()->json(['message' => 'Inscrição realizada com sucesso'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function cancelar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $this->service->cancelar($validated['usuario_id'], $validated['curso_id']);

        return response()->json(['message' => 'Inscrição cancelada com sucesso'], 200);
    }

    public function cursosDoUsuario(int $usuario_id): JsonResponse
    {
        try {
            $cursos = $this->service->cursosDoUsuario($usuario_id);
            return response()->json($cursos, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function usuariosDoCurso(int $curso_id): JsonResponse
    {
        try {
            $usuarios = $this->service->usuariosDoCurso($curso_id);
            return response()->json($usuarios, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
