<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAvaliacaoRequest;
use App\Http\Resources\AvaliacaoResource;
use App\Services\AvaliacaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AvaliacaoController extends Controller
{
    public function __construct(
        private AvaliacaoService $service
    ) {}

    public function index(): JsonResponse
    {
        $avaliacoes = $this->service->listarPorUsuario(Auth::id());
        return AvaliacaoResource::collection($avaliacoes)->response();
    }

    public function store(StoreAvaliacaoRequest $request): JsonResponse
    {
        $avaliacao = $this->service->criar($request->validated(), Auth::id());
        return (new AvaliacaoResource($avaliacao))->response()->setStatusCode(201);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id, Auth::id());
            return response()->json(['message' => 'Avaliação removida com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function avaliacaoPorCurso(int $id_curso): JsonResponse
    {
        $resultado = $this->service->listarPorCurso($id_curso);

        return response()->json([
            'avaliacoes' => AvaliacaoResource::collection($resultado['avaliacoes']),
            'classificacao' => $resultado['classificacao'],
        ], 200);
    }
}
