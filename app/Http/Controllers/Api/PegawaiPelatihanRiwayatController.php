<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ✅ Perhatikan ini
use App\Models\PegawaiPelatihanRiwayat;
use App\Http\Requests\StorePegawaiPelatihanRiwayatRequest;

class PegawaiPelatihanRiwayatController extends Controller
{
    public function index()
    {
        return PegawaiPelatihanRiwayat::with('pegawai')->get();
    }

    public function store(StorePegawaiPelatihanRiwayatRequest $request)
    {
        $riwayat = PegawaiPelatihanRiwayat::create($request->validated());
        return response()->json($riwayat, 201);
    }

    public function show($id)
    {
        return PegawaiPelatihanRiwayat::with('pegawai')->findOrFail($id);
    }

    public function update(StorePegawaiPelatihanRiwayatRequest $request, $id)
    {
        $riwayat = PegawaiPelatihanRiwayat::findOrFail($id);
        $riwayat->update($request->validated());
        return response()->json($riwayat);
    }

    public function destroy($id)
    {
        $riwayat = PegawaiPelatihanRiwayat::findOrFail($id);
        $riwayat->delete();
        return response()->json(['message' => 'Riwayat deleted successfully.']);
    }
}
