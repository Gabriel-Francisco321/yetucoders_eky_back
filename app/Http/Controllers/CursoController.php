<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCursoRequest;
use App\Http\Requests\UpdateCursoRequest;
use App\Http\Resources\CursoResource;
use App\Services\CursoService;
use Illuminate\Http\JsonResponse;

class CursoController extends Controller
{
    public function __construct(
        private CursoService $service
    ) {}

    public function index(): JsonResponse
    {
        $cursos = $this->service->listar();
        return CursoResource::collection($cursos)->response();
    }

    public function store(StoreCursoRequest $request): JsonResponse
    {
        $curso = $this->service->criar($request->validated());
        return (new CursoResource($curso))->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $curso = $this->service->buscar($id);
            return (new CursoResource($curso))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function update(UpdateCursoRequest $request, int $id): JsonResponse
    {
        try {
            $curso = $this->service->actualizar($id, $request->validated());
            return (new CursoResource($curso))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id);
            return response()->json(['message' => 'Curso removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function trashed(): JsonResponse
    {
        $cursos = $this->service->listarEliminados();
        return CursoResource::collection($cursos)->response();
    }

    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminarPermanente($id);
            return response()->json(['message' => 'Curso permanentemente deletado.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $curso = $this->service->restaurar($id);
            return (new CursoResource($curso))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
