<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FinancieroController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoliticasController;
use App\Http\Controllers\GuardarEntidadesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth/login',[AuthController::class,'authLogin'])->name('authlogin');
  

Route::middleware('auth:sanctum')->group( function () {

    Route::get('consulta_financiero/{nit}',[FinancieroController::class,'consultaFinanciero'])->name('consultaFinanciero');
    Route::post('/auth/logout',[AuthController::class,'authLogout'])->name('authlogout');
});

Route::post('/enviar-email',[AuthController::class,'enviarEmail'])->name('enviarEmail');
Route::post('/recuperar-password',[AuthController::class,'recuperarPassword'])->name('recuperarPassword');
Route::post('/creacion-cuenta',[AuthController::class,'creacionCuentaEmail'])->name('creacionCuentaEmail');
Route::get('/politicas', [PoliticasController::class, 'obtenerPoliticas']);
// Route::post('/guardar-entidades', [GuardarEntidadesController::class, 'guardarEntidades'])->name('guardarEntidades');
Route::get('/batch/{id}', [AuthController::class, 'consultarTarea'])->name('consultarTarea');