<?php

use App\Http\Controllers\InstrutorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;


/* ----------ROTAS PARA USUÁRIOS---------- */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('usuarios', 'App\\Http\\Controllers\\UsuarioController');

// Rotas adicionais para soft delete e hard delete
Route::get('usuarios/trashed/list', [UsuarioController::class, 'trashed']);
Route::delete('usuarios/{id}/force', [UsuarioController::class, 'forceDestroy']);
Route::post('usuarios/{id}/restore', [UsuarioController::class, 'restore']);


/* ----------ROTAS PARA INSTRUTORES---------- */
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('instrutores', InstrutorController::class);
});


/* ----------ROTAS PARA CURSOS---------- */
Route::apiResource('cursos', 'App\Http\Controller\CursoController');

// Rotas adicionais para soft delete e hard delete
Route::get('cursos/trashed/list', 'App\Http\Controller\CursoController@trashed');
Route::delete('cursos/{id}/force', 'App\Http\Controller\CursoController@forceDestroy');
Route::post('cursos/{id}/restore', 'App\Http\Controller\CursoController@restore');