<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    public function __construct(
        private UsuarioService $service
    ) {}

    public function index(): JsonResponse
    {
        $usuarios = $this->service->listar();
        return UsuarioResource::collection($usuarios)->response();
    }

    public function store(StoreUsuarioRequest $request): JsonResponse
    {
        $usuario = $this->service->criar($request->validated());
        return (new UsuarioResource($usuario))->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $usuario = $this->service->buscar($id);
            return (new UsuarioResource($usuario))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function update(UpdateUsuarioRequest $request, int $id): JsonResponse
    {
        try {
            $usuario = $this->service->actualizar($id, $request->validated());
            return (new UsuarioResource($usuario))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminar($id);
            return response()->json(['message' => 'Usuário removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function trashed(): JsonResponse
    {
        $usuarios = $this->service->listarEliminados();
        return UsuarioResource::collection($usuarios)->response();
    }

    public function forceDestroy(int $id): JsonResponse
    {
        try {
            $this->service->eliminarPermanente($id);
            return response()->json(['message' => 'Usuário permanentemente deletado.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $usuario = $this->service->restaurar($id);
            return (new UsuarioResource($usuario))->response();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
