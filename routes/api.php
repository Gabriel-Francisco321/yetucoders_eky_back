<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AulaController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\InstrutorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CursoUsuarioController;

/* ---------- USUÁRIO LOGADO ---------- */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/* ---------- USUÁRIOS ---------- */
Route::apiResource('usuarios', UsuarioController::class);

Route::get('usuarios/trashed/list', [UsuarioController::class, 'trashed']);
Route::delete('usuarios/{id}/force', [UsuarioController::class, 'forceDestroy']);
Route::post('usuarios/{id}/restore', [UsuarioController::class, 'restore']);


/* ---------- CATEGORIAS ---------- */
Route::apiResource('categorias', CategoriaController::class);

Route::get('categorias/trashed/list', [CategoriaController::class, 'trashed']);
Route::delete('categorias/{id}/force', [CategoriaController::class, 'forceDestroy']);
Route::post('categorias/{id}/restore', [CategoriaController::class, 'restore']);


/* ---------- CURSOS ---------- */
Route::apiResource('cursos', CursoController::class);

Route::get('cursos/trashed/list', [CursoController::class, 'trashed']);
Route::delete('cursos/{id}/force', [CursoController::class, 'forceDestroy']);
Route::post('cursos/{id}/restore', [CursoController::class, 'restore']);


/* ---------- INSTRUTORES E AULAS (PROTEGIDO) ---------- */
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('instrutores', InstrutorController::class);
    Route::apiResource('aulas', AulaController::class);

    Route::get('cursos/{id_curso}/aulas', [AulaController::class, 'indexPorCurso']);

    Route::apiResource('avaliacoes', AvaliacaoController::class);

    Route::get('curso/{id_curso}/avaliacoes', [AvaliacaoController::class, 'avaliacaoPorCurso']);
});


/* ---------- RELACIONAMENTO CURSO ↔ USUÁRIO ---------- */
Route::post('/inscrever', [CursoUsuarioController::class, 'inscrever']);
Route::post('/cancelar-inscricao', [CursoUsuarioController::class, 'cancelar']);

Route::get('/usuarios/{id}/cursos', [CursoUsuarioController::class, 'cursosDoUsuario']);
Route::get('/curso/{id}/usuarios', [CursoUsuarioController::class, 'usuariosDoCurso']);
