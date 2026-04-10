<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAulaRequest;
use App\Http\Requests\UpdateAulaRequest;
use App\Http\Resources\AulaResource;
use App\Services\AulaService;
use Illuminate\Http\JsonResponse;

class AulaController extends Controller
{
    public function __construct(
        private AulaService $service
    ) {}

    public function index(): JsonResponse
    {
        $aulas = $this->service->listar();
        return AulaResource::collection($aulas)->response();
    }

    public function indexPorCurso(int $id_curso): JsonResponse
    {
        $aulas = $this->service->listarPorCurso($id_curso);
        return AulaResource::collection($aulas)->response();
    }

    public function show(int $aula): JsonResponse
    {
        try {
            $aulaEncontrada = $this->service->buscar($aula);
            return (new AulaResource($aulaEncontrada))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function store(StoreAulaRequest $request): JsonResponse
    {
        $aula = $this->service->criar($request->validated());
        return (new AulaResource($aula))->response()->setStatusCode(201);
    }

    public function update(UpdateAulaRequest $request, int $aula): JsonResponse
    {
        try {
            $aulaActualizada = $this->service->actualizar($aula, $request->validated());
            return (new AulaResource($aulaActualizada))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $aula): JsonResponse
    {
        try {
            $this->service->eliminar($aula);
            return response()->json(['message' => 'Aula removida com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
