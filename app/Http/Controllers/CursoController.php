<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all();
        return response()->json($cursos, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|max:500',
            'objectivos' => 'required|max:300',
            'requisitos' => 'nullable|max:300',
            'preco' => 'nullable|decimal:0,2',
            'nivel' => 'required|in:Iniciante,Intermedi\u00e1rio,Avan\u00e7ado',
            'id_instrutor' => 'required|integer|exists:instrutores,id',
            'id_categoria' => 'required|integer|exists:categorias,id',
        ]);

        $curso = Curso::create($validated);

        return response()->json($curso, 201);
    }

    public function show(string $id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        return response()->json($curso, 200);
    }

    public function update(Request $request, string $id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|max:500',
            'objectivos' => 'required|max:300',
            'requisitos' => 'nullable|max:300',
            'preco' => 'nullable|decimal:0,2',
            'nivel' => 'required|in:Iniciante,Intermedi\u00e1rio,Avan\u00e7ado',
            'id_instrutor' => ['required', 'integer', 'exists:instrutores,id'],
            'id_categoria' => 'required|integer|exists:categorias,id',
        ]);

        $curso->update($validated);

        return response()->json($curso, 200);
    }

    public function destroy(string $id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        $curso->delete();

        return response()->json(['message' => 'Curso deletado com sucesso'], 200);
    }

    public function trashed()
    {
        $cursosDeletados = Curso::onlyTrashed()->get();
        return response()->json($cursosDeletados, 200);
    }

    public function forceDestroy(string $id)
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        $curso->forceDelete();

        return response()->json(['message' => 'Curso permanentemente deletado'], 200);
    }

    public function restore(string $id)
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso n\u00e3o encontrado'], 404);
        }

        $curso->restore();

        return response()->json(['message' => 'Curso restaurado com sucesso', 'data' => $curso], 200);
    }
}
