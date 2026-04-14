<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'senha' => 'required|string|min:6',
            'tipo' => 'required|in:aluno,instrutor',
        ]);

        $validated['senha'] = bcrypt($validated['senha']);
        $usuario = Usuario::create($validated);

        return response()->json($usuario, 201);
    }

    public function show(string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json($usuario, 200);
    }

    public function update(Request $request, string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $validated = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('usuarios')->ignore($id)],
            'senha' => 'sometimes|string|min:6',
            'tipo' => 'sometimes|in:aluno,instrutor',
        ]);

        if (isset($validated['senha'])) {
            $validated['senha'] = bcrypt($validated['senha']);
        }

        $usuario->update($validated);

        return response()->json($usuario, 200);
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
    }

    public function trashed()
    {
        $usuariosDeletados = Usuario::onlyTrashed()->get();
        return response()->json($usuariosDeletados, 200);
    }

    public function forceDestroy(string $id)
    {
        $usuario = Usuario::withTrashed()->find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $usuario->forceDelete();

        return response()->json(['message' => 'Usuário permanentemente deletado'], 200);
    }

    public function restore(string $id)
    {
        $usuario = Usuario::withTrashed()->find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $usuario->restore();

        return response()->json(['message' => 'Usuário restaurado com sucesso', 'data' => $usuario], 200);
    }
}
