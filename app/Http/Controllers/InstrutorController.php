<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstrutorRequest;
use App\Http\Requests\UpdateInstrutorRequest;
use App\Http\Resources\InstrutorResource;
use App\Models\Instrutor;
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

    public function trashed()
    {
        $instrutoresDeletados = Instrutor::onlyTrashed()->get();
        return response()->json($instrutoresDeletados, 200);
    }

    public function forceDestroy(string $id)
    {
        $instrutor = Instrutor::withTrashed()->find($id);

        if (!$instrutor) {
            return response()->json(['message' => 'Instrutor n\u00e3o encontrado'], 404);
        }

        $instrutor->forceDelete();

        return response()->json(['message' => 'Instrutor permanentemente deletado'], 200);
    }

    public function restore(string $id)
    {
        $instrutor = Instrutor::withTrashed()->find($id);

        if (!$instrutor) {
            return response()->json(['message' => 'Instrutor n\u00e3o encontrado'], 404);
        }

        $instrutor->restore();

        return response()->json(['message' => 'Instrutor restaurado com sucesso', 'data' => $instrutor], 200);
    }
}
