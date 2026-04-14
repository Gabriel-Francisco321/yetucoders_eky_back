<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|max:500',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json($categoria, 201);
    }

    public function show(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria n\u00e3o encontrada'], 404);
        }

        return response()->json($categoria, 200);
    }

    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria n\u00e3o encontrada'], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|max:500',
        ]);

        $categoria->update($validated);

        return response()->json($categoria, 200);
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria n\u00e3o encontrada'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria deletada com sucesso'], 200);
    }

    public function trashed()
    {
        $categoriasDeletadas = Categoria::onlyTrashed()->get();
        return response()->json($categoriasDeletadas, 200);
    }

    public function forceDestroy(string $id)
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria n\u00e3o encontrada'], 404);
        }

        $categoria->forceDelete();

        return response()->json(['message' => 'Categoria permanentemente deletada'], 200);
    }

    public function restore(string $id)
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoria n\u00e3o encontrada'], 404);
        }

        $categoria->restore();

        return response()->json(['message' => 'Categoria restaurada com sucesso', 'data' => $categoria], 200);
    }
}
