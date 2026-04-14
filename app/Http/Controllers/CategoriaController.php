<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function __construct(
        private CategoriaService $service
    ) {}

    public function index(): JsonResponse
    {
        $categorias = $this->service->listar();
        return CategoriaResource::collection($categorias)->response();
    }

    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        $categoria = $this->service->criar($request->validated());
        return (new CategoriaResource($categoria))->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $categoria = $this->service->buscar($id);
            return (new CategoriaResource($categoria))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function update(UpdateCategoriaRequest $request, int $id): JsonResponse
    {
        try {
            $categoria = $this->service->actualizar($id, $request->validated());
            return (new CategoriaResource($categoria))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id);
            return response()->json(['message' => 'Categoria removida com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function trashed(): JsonResponse
    {
        $categorias = $this->service->listarEliminados();
        return CategoriaResource::collection($categorias)->response();
    }

    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminarPermanente($id);
            return response()->json(['message' => 'Categoria permanentemente deletada.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $categoria = $this->service->restaurar($id);
            return (new CategoriaResource($categoria))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
