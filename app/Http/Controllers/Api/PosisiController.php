<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Posisi;
use Illuminate\Http\Request;

class PosisiController extends Controller
{
    // Tampilkan semua data posisi
    public function index()
    {
        $data = Posisi::all();
        return response()->json($data);
    }

    // Simpan data posisi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'lama_menjabat' => 'nullable|integer|min:0',
        ]);

        $data = Posisi::create($validated);
        return response()->json($data, 201);
    }

    // Tampilkan detail posisi berdasarkan ID
    public function show($id)
    {
        $data = Posisi::findOrFail($id);
        return response()->json($data);
    }

    // Perbarui data posisi
    public function update(Request $request, $id)
    {
        $data = Posisi::findOrFail($id);

        $validated = $request->validate([
            'nama_posisi' => 'sometimes|required|string|max:255',
            'lama_menjabat' => 'nullable|integer|min:0',
        ]);

        $data->update($validated);
        return response()->json($data);
    }

    // Hapus data posisi
    public function destroy($id)
    {
        $data = Posisi::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Posisi berhasil dihapus.']);
    }
}
