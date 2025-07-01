<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanSyarat;
use Illuminate\Http\Request;

class PelatihanSyaratController extends Controller
{
    // Tampilkan semua data syarat pelatihan + relasi pelatihan
    public function index()
    {
        $data = PelatihanSyarat::with('pelatihan')->get();
        return response()->json($data);
    }

    // Simpan data syarat pelatihan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'kode_pelatihan' => 'required|string|max:100',
        ]);

        $exists = PelatihanSyarat::where('pelatihan_id', $validated['pelatihan_id'])
            ->where('kode_pelatihan', $validated['kode_pelatihan'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Syarat pelatihan ini sudah ada.'
            ], 422);
        }

        $data = PelatihanSyarat::create($validated);
        return response()->json($data->load('pelatihan'), 201);
    }

    // Tampilkan satu syarat berdasarkan ID + relasi pelatihan
    public function show($id)
    {
        $data = PelatihanSyarat::with('pelatihan')->findOrFail($id);
        return response()->json($data);
    }

    // Perbarui data syarat
    public function update(Request $request, $id)
    {
        $data = PelatihanSyarat::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'kode_pelatihan' => 'sometimes|required|string|max:100',
        ]);

        $data->update($validated);

        return response()->json($data->load('pelatihan'));
    }

    // Hapus syarat pelatihan
    public function destroy($id)
    {
        $data = PelatihanSyarat::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Syarat pelatihan berhasil dihapus.']);
    }
}
