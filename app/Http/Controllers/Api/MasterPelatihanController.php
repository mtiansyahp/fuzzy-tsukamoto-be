<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterPelatihan;
use Illuminate\Http\Request;

class MasterPelatihanController extends Controller
{
    public function index()
    {
        // Tampilkan semua data pelatihan
        return response()->json(MasterPelatihan::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pelatihan' => 'required|string|max:100|unique:master_pelatihan,kode_pelatihan',
            'nama_pelatihan' => 'required|string|max:255',
        ]);

        $pelatihan = MasterPelatihan::create($validated);
        return response()->json($pelatihan, 201);
    }

    public function show($id)
    {
        $pelatihan = MasterPelatihan::findOrFail($id);
        return response()->json($pelatihan);
    }

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

    public function destroy($id)
    {
        $pelatihan = MasterPelatihan::findOrFail($id);
        $pelatihan->delete();
        return response()->json(['message' => 'Pelatihan berhasil dihapus.']);
    }
}
