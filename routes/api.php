<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\InstrutorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CursoController;


/* ----------ROTAS PARA USUÁRIOS---------- */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('usuarios', 'App\\Http\\Controllers\\UsuarioController');

// Rotas adicionais para hard delete e restauração
Route::get('usuarios/trashed/list', [UsuarioController::class, 'trashed']);
Route::delete('usuarios/{id}/force', [UsuarioController::class, 'forceDestroy']);
Route::post('usuarios/{id}/restore', [UsuarioController::class, 'restore']);


/* ----------ROTAS PARA CATEGORIAS---------- */
Route::apiResource('categorias', CategoriaController::class);

// Rotas adicionais para hard delete e restauração
Route::get('categorias/trashed/list', [CategoriaController::class, 'trashed']);
Route::delete('categorias/{id}/force', [CategoriaController::class, 'forceDestroy']);
Route::post('categorias/{id}/restore', [CategoriaController::class, 'restore']);


/* ----------ROTAS PARA INSTRUTORES/AULAS---------- */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('cursos/{id_curso}/aulas', [AulaController::class, 'indexPorCurso']);
    Route::apiResource('instrutores', InstrutorController::class);
    Route::apiResource('aulas', AulaController::class);
});


/* ----------ROTAS PARA CURSOS---------- */
Route::apiResource('cursos', CursoController::class);

// Rotas adicionais para hard delete e restauração
Route::get('cursos/trashed/list', [CursoController::class, 'trashed']);
Route::delete('cursos/{id}/force', [CursoController::class, 'forceDestroy']);
Route::post('cursos/{id}/restore', [CursoController::class, 'restore']);