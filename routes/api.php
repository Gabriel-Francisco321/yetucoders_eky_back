<?php

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

/*
 * Rotas geradas automaticamente pelo apiResource:
 *
 * GET    /api/instrutores         → index()    listar todos
 * POST   /api/instrutores         → store()    criar
 * GET    /api/instrutores/{id}    → show()     ver um
 * PUT    /api/instrutores/{id}    → update()   actualizar
 * DELETE /api/instrutores/{id}    → destroy()  eliminar
 */
