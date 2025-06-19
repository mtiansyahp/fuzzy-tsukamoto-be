<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FuzzyController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();;
});
Route::get('/penilaian', [FuzzyController::class, 'getAllPenilaian']);
Route::get('/penilaian/{id}', [FuzzyController::class, 'getPenilaianDetail']);
Route::get('/log-penilaian/{id}', [FuzzyController::class, 'getLogPenilaianByUser']);
Route::get('/uji-penilaian/{id}', [FuzzyController::class, 'getDetailPenilaianWithLog']);
