<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function index()
    {
        $avaliacoes = Avaliacao::where('id_usuario', Auth::id())->get();
        return response()->json($avaliacoes, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nota' => 'required|integer|min:0|max:100',
            'comentario' => 'nullable|string',
            'id_curso' => 'required|integer|exists:cursos,id',
        ]);

        $avaliacao = Avaliacao::create([
            'nota' => $validated['nota'],
            'comentario' => $validated['comentario'] ?? null,
            'id_usuario' => Auth::id(),
            'id_curso' => $validated['id_curso'],
        ]);

        return response()->json($avaliacao, 201);
    }

    public function destroy($id)
    {
        $avaliacao = Avaliacao::where('id', $id)
            ->where('id_usuario', Auth::id())
            ->first();

        if (!$avaliacao) {
            return response()->json(['message' => 'Avalia\u00e7\u00e3o n\u00e3o encontrada'], 404);
        }

        $avaliacao->delete();

        return response()->json(['message' => 'Avalia\u00e7\u00e3o removida com sucesso'], 200);
    }

    public function avaliacaoPorCurso(string $id_curso)
    {
        $avaliacoes = Avaliacao::where('id_curso', $id_curso)->get();
        $classificacao = Avaliacao::where('id_curso', $id_curso)->avg('nota');

        return response()->json([
            'avaliacoes' => $avaliacoes,
            'classificacao' => $classificacao,
        ], 200);
    }
}
