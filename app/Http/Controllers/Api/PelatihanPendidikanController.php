<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanPendidikan;
use Illuminate\Http\Request;

class PelatihanPendidikanController extends Controller
{
    // Menampilkan semua data pelatihan-pendidikan
    public function index()
    {
        $data = PelatihanPendidikan::all();
        return response()->json($data);
    }

    // Menyimpan data baru pelatihan-pendidikan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'pendidikan_terakhir' => 'required|string|max:255',
        ]);

        $data = PelatihanPendidikan::create($validated);

        return response()->json($data, 201);
    }

    // Menampilkan detail satu data berdasarkan ID
    public function show($id)
    {
        $data = PelatihanPendidikan::findOrFail($id);
        return response()->json($data);
    }

    // Memperbarui data pelatihan-pendidikan
    public function update(Request $request, $id)
    {
        $data = PelatihanPendidikan::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'pendidikan_terakhir' => 'sometimes|required|string|max:255',
        ]);

        $data->update($validated);

        return response()->json($data);
    }

    // Menghapus data
    public function destroy($id)
    {
        $data = PelatihanPendidikan::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Syarat pendidikan berhasil dihapus.']);
    }
}
