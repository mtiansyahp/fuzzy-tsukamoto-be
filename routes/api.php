<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FuzzyController;
use App\Http\Controllers\Api\PelatihanController;
use App\Http\Controllers\Api\MasterPelatihanController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\LogPenilaianController;
use App\Http\Controllers\Api\JurusanController;
use App\Http\Controllers\Api\PelatihanJurusanController;
use App\Http\Controllers\Api\PelatihanMaxUmurController;
use App\Http\Controllers\Api\PelatihanPendidikanController;
use App\Http\Controllers\Api\PelatihanPesertaController;
use App\Http\Controllers\Api\PelatihanSertifikasiController;
use App\Http\Controllers\Api\PelatihanSyaratController;
use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\PenilaianWajibController;
use App\Http\Controllers\Api\PosisiController;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\PegawaiPelatihanRiwayatController;


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



// Route::apiResource('pegawai', PegawaiController::class);
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('pegawai', PegawaiController::class);
Route::apiResource('pelatihan', PelatihanController::class);
Route::apiResource('master-pelatihan', MasterPelatihanController::class);
Route::apiResource('log-penilaian', LogPenilaianController::class);
Route::apiResource('jurusan', JurusanController::class);
Route::apiResource('pelatihan-jurusan', PelatihanJurusanController::class);
Route::apiResource('pelatihan-max-umur', PelatihanMaxUmurController::class);
Route::apiResource('pelatihan-pendidikan', PelatihanPendidikanController::class);
Route::apiResource('pelatihan-peserta', PelatihanPesertaController::class);
Route::apiResource('pelatihan-sertifikasi', PelatihanSertifikasiController::class);
Route::apiResource('pelatihan-syarat', PelatihanSyaratController::class);
Route::apiResource('penilaian', PenilaianController::class);
Route::apiResource('penilaian-wajib', PenilaianWajibController::class);
Route::apiResource('posisi', PosisiController::class);
Route::apiResource('sertifikasi', SertifikasiController::class);
Route::apiResource('pegawai-riwayat', PegawaiPelatihanRiwayatController::class);
// routes/api.php
Route::get('/pegawai/reference-data', [PegawaiController::class, 'getReferenceData']);
Route::apiResource('master-pelatihan', MasterPelatihanController::class);

Route::get('/fuzzy/hitung/{id_peserta}', [FuzzyController::class, 'hitungFuzzyTsukamoto']);
Route::get('/fuzzy/penilaian/{id}', [\App\Http\Controllers\Api\FuzzyController::class, 'hitungBatchPeserta']);
