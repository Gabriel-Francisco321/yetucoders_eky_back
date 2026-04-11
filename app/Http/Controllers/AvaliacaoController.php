<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avaliacoes = Avaliacao::where('id_usuario', Auth::id())->get();
        return response()->json($avaliacoes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notas'=> 'required|integer|min:0|max:100',
            'comentario'=>'nullable|string',
            'id_curso'=>'required|integer|min:1'
        ]);
        $avaliacao = Avaliacao::create([
            'nota'=>$request->nota,
            'commentario'=>$request->comentario,
            'id_usuario'=>Auth::id(),
            'id_curso'=>$request->id_curso
        ]);

        return response()->json($avaliacao, 201);
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $avaliacao = Avaliacao::where('id',$id)
                            ->where('id_usuario', Auth::id())
                            ->first();

        $avaliacao->delete();
        return response()->json([
            'mensagem'=>'Avalição removida com sucesso'
        ], 200);
    }

    /**
     * Retorna uma lista de todas as avaliações de um curso 
     */
    public function avaliacaoPorCurso(string $id_curso){
        $avaliacoes = Avaliacao::where('id_curso', $id_curso)->get();
        $clacificacao = Avaliacao::where('id_curso', $id_curso)->avg('nota');
        return response()->json([$avaliacoes, $clacificacao], 200);
    }
}
