<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AulaController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CursoUsuarioController;
use App\Http\Controllers\InstrutorController;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('usuarios', UsuarioController::class);
Route::get('usuarios/trashed/list', [UsuarioController::class, 'trashed']);
Route::delete('usuarios/{id}/force', [UsuarioController::class, 'forceDestroy']);
Route::post('usuarios/{id}/restore', [UsuarioController::class, 'restore']);

Route::apiResource('categorias', CategoriaController::class);
Route::get('categorias/trashed/list', [CategoriaController::class, 'trashed']);
Route::delete('categorias/{id}/force', [CategoriaController::class, 'forceDestroy']);
Route::post('categorias/{id}/restore', [CategoriaController::class, 'restore']);

Route::apiResource('cursos', CursoController::class);
Route::get('cursos/trashed/list', [CursoController::class, 'trashed']);
Route::delete('cursos/{id}/force', [CursoController::class, 'forceDestroy']);
Route::post('cursos/{id}/restore', [CursoController::class, 'restore']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('instrutores', InstrutorController::class);
    Route::get('instrutores/trashed/list', [InstrutorController::class, 'trashed']);
    Route::delete('instrutores/{id}/force', [InstrutorController::class, 'forceDestroy']);
    Route::post('instrutores/{id}/restore', [InstrutorController::class, 'restore']);

    Route::apiResource('aulas', AulaController::class);
    Route::get('cursos/{id_curso}/aulas', [AulaController::class, 'indexPorCurso']);

    Route::apiResource('avaliacoes', AvaliacaoController::class)->only(['index', 'store', 'destroy']);
    Route::get('cursos/{id_curso}/avaliacoes', [AvaliacaoController::class, 'avaliacaoPorCurso']);
});

Route::post('inscrever', [CursoUsuarioController::class, 'inscrever']);
Route::post('cancelar-inscricao', [CursoUsuarioController::class, 'cancelar']);
Route::get('usuarios/{id}/cursos', [CursoUsuarioController::class, 'cursosDoUsuario']);
Route::get('cursos/{id}/usuarios', [CursoUsuarioController::class, 'usuariosDoCurso']);
