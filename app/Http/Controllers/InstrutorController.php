<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstrutorRequest;
use App\Http\Requests\UpdateInstrutorRequest;
use App\Http\Resources\InstrutorResource;
use App\Services\InstrutorService;
use Illuminate\Http\JsonResponse;

class InstrutorController extends Controller
{
    public function __construct(
        private InstrutorService $service
    ) {}

    public function index(): JsonResponse
    {
        $instrutores = $this->service->listar();
        return InstrutorResource::collection($instrutores)->response();
    }

    public function show(int $id): JsonResponse
    {
        try {
            $instrutor = $this->service->buscar($id);
            return (new InstrutorResource($instrutor))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function store(StoreInstrutorRequest $request): JsonResponse
    {
        $instrutor = $this->service->criar($request->validated());
        return (new InstrutorResource($instrutor))->response()->setStatusCode(201);
    }

    public function update(UpdateInstrutorRequest $request, int $id): JsonResponse
    {
        try {
            $instrutor = $this->service->actualizar($id, $request->validated());
            return (new InstrutorResource($instrutor))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id);
            return response()->json(['message' => 'Instrutor removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function trashed(): JsonResponse
    {
        $instrutores = $this->service->listarEliminados();
        return InstrutorResource::collection($instrutores)->response();
    }

    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminarPermanente($id);
            return response()->json(['message' => 'Instrutor permanentemente deletado.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $instrutor = $this->service->restaurar($id);
            return (new InstrutorResource($instrutor))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
