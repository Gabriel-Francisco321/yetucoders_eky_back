<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descrição' => 'required|max:500',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json($categoria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id);

        if (empty($categoria)) {
            return response()->json(['message' => 'Categoria não encontrado'], 404);
        }

        return response()->json($categoria, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (empty($categoria)) {
            return response()->json(['message' => 'Categoria não encontrado'], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descrição' => 'required|max:500',
        ]);

        $categoria->update($validated);

        return response()->json($categoria, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (empty($categoria)) {
            return response()->json(['message' => 'Categoria não encontrado'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria deletado com sucesso'], 200);
    }
    

    

    //---------------------- FUNÇÕES ADICIONAIS HARD DELETE E RESTAURAÇÃO ---------------------------

    /**
     * Display a listing of soft deleted users.
     */
    public function trashed()
    {
        $categoriasDeletados = Categoria::onlyTrashed()->get();
        return response()->json($categoriasDeletados, 200);
    }

    /**
     * Permanently delete a soft deleted user.
     */
    public function forceDestroy(string $id)
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (empty($categoria)) {
            return response()->json(['message' => 'Categoria não encontrado'], 404);
        }

        $categoria->forceDelete();

        return response()->json(['message' => 'Categoria permanentemente deletado'], 200);
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore(string $id)
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (empty($categoria)) {
            return response()->json(['message' => 'Categoria não encontrado'], 404);
        }

        $categoria->restore();

        return response()->json(['message' => 'Categoria restaurado com sucesso', 'data' => $categoria], 200);
    }
}
