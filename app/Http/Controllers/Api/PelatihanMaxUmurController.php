<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanMaxUmur;
use Illuminate\Http\Request;

class PelatihanMaxUmurController extends Controller
{
    // Tampilkan semua data max umur pelatihan
    public function index()
    {
        $data = PelatihanMaxUmur::all();
        return response()->json($data);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'max_umur' => 'required|integer|min:1',
        ]);

        $data = PelatihanMaxUmur::create($validated);

        return response()->json($data, 201);
    }

    // Tampilkan detail berdasarkan ID
    public function show($id)
    {
        $data = PelatihanMaxUmur::findOrFail($id);
        return response()->json($data);
    }

    // Perbarui data
    public function update(Request $request, $id)
    {
        $data = PelatihanMaxUmur::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'max_umur' => 'sometimes|required|integer|min:1',
        ]);

        $data->update($validated);

        return response()->json($data);
    }

    // Hapus data
    public function destroy($id)
    {
        $data = PelatihanMaxUmur::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Batas umur pelatihan berhasil dihapus.']);
    }
}
