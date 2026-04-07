<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::all();
        return response()->json($cursos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descrição' => 'required|max:500',
            'objectivos' => 'required|max:300',
            'requisitos' => 'max:300',
            'preco' => 'decimal:0,2',
            'nivel' => 'required|in:Iniciante,Intermediário,Avançado',
            'id_instrutor' => 'required|integer|exists:instrutor,id|unique:cursos,id_instrutor',
            'id_categoria' => 'required|integer|exists:categoria,id',
        ]);

        $curso = Curso::create($validated);

        return response()->json($curso, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $curso = Curso::find($id);

        if (empty($curso)) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        return response()->json($curso, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $curso = Curso::find($id);

        if (empty($curso)) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descrição' => 'required|max:500',
            'objectivos' => 'required|max:300',
            'requisitos' => 'max:300',
            'preco' => 'decimal:0,2',
            'nivel' => 'required|in:Iniciante,Intermediário,Avançado',
            'id_instrutor' => 'required|integer|exists:instrutor,id|unique:cursos,id_instrutor',
            'id_categoria' => 'required|integer|exists:categoria,id',
        ]);

        $curso->update($validated);

        return response()->json($curso, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $curso = Curso::find($id);

        if (empty($curso)) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        $curso->delete();

        return response()->json(['message' => 'Curso deletado com sucesso'], 200);
    }


    

    //---------------------- FUNÇÕES ADICIONAIS HARD DELETE E RESTAURAÇÃO ---------------------------

    /**
     * Display a listing of soft deleted users.
     */
    public function trashed()
    {
        $cursosDeletados = Curso::onlyTrashed()->get();
        return response()->json($cursosDeletados, 200);
    }

    /**
     * Permanently delete a soft deleted user.
     */
    public function forceDestroy(string $id)
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        $curso->forceDelete();

        return response()->json(['message' => 'Curso permanentemente deletado'], 200);
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore(string $id)
    {
        $curso = Curso::withTrashed()->find($id);

        if (!$curso) {
            return response()->json(['message' => 'Curso não encontrado'], 404);
        }

        $curso->restore();

        return response()->json(['message' => 'Curso restaurado com sucesso', 'data' => $curso], 200);
    }
}
