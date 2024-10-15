<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FinancieroController;
use App\Http\Controllers\AuthController;
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
    // Route::get('consulta_financiero/{nit}',[FinancieroController::class,'consultaFinanciero'])->name('consultaFinanciero');
    Route::get('consulta_financiero/{nit}',[FinancieroController::class,'consultaFinanciero'])->name('consultaFinanciero');
    Route::post('/auth/logout',[AuthController::class,'authLogout'])->name('authlogout');
});