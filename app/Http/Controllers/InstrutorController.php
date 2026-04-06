<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstrutorRequest;
use App\Http\Requests\UpdateInstrutorRequest;
use App\Http\Resources\InstrutorResource;
use App\Services\InstrutorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class InstrutorController extends Controller
{
    public function __construct(
        private InstrutorService $service
    ) {}

    // GET /api/instrutores
    public function index(): JsonResponse
    {
        $instrutores = $this->service->listar();
        return InstrutorResource::collection($instrutores)->response();
    }

    // GET /api/instrutores/{id}
    public function show(int $id): JsonResponse
    {
        try {
            $instrutor = $this->service->buscar($id);
            return (new InstrutorResource($instrutor))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    // POST /api/instrutores
    public function store(StoreInstrutorRequest $request): JsonResponse
    {
        $instrutor = $this->service->criar($request->validated());
        return (new InstrutorResource($instrutor))->response()->setStatusCode(201);
    }

    // PUT /api/instrutores/{id}
    public function update(UpdateInstrutorRequest $request, int $id): JsonResponse
    {
        try {
            $instrutor = $this->service->actualizar($id, $request->validated());
            return (new InstrutorResource($instrutor))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    // DELETE /api/instrutores/{id}
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id);
            return response()->json(['message' => 'Instrutor removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
