<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPelatihan;
use Illuminate\Http\Request;

class MasterPelatihanController extends Controller
{
    // Tampilkan semua data pelatihan
    public function index()
    {
        $pelatihan = MasterPelatihan::all();
        return response()->json($pelatihan);
    }

    // Simpan data pelatihan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pelatihan' => 'required|string|max:100|unique:master_pelatihan,kode_pelatihan',
            'nama_pelatihan' => 'required|string|max:255',
        ]);

        $pelatihan = MasterPelatihan::create($validated);

        return response()->json($pelatihan, 201);
    }

    // Tampilkan detail pelatihan berdasarkan ID
    public function show($id)
    {
        $pelatihan = MasterPelatihan::findOrFail($id);
        return response()->json($pelatihan);
    }

    // Perbarui data pelatihan
    public function update(Request $request, $id)
    {
        $pelatihan = MasterPelatihan::findOrFail($id);

        $validated = $request->validate([
            'kode_pelatihan' => 'sometimes|required|string|max:100|unique:master_pelatihan,kode_pelatihan,' . $id . ',id_pelatihan',
            'nama_pelatihan' => 'sometimes|required|string|max:255',
        ]);

        $pelatihan->update($validated);

        return response()->json($pelatihan);
    }

    // Hapus data pelatihan
    public function destroy($id)
    {
        $pelatihan = MasterPelatihan::findOrFail($id);
        $pelatihan->delete();

        return response()->json(['message' => 'Pelatihan berhasil dihapus.']);
    }
}
