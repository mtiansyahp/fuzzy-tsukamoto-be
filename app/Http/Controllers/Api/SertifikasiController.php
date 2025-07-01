<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    // Tampilkan semua sertifikasi
    public function index()
    {
        $data = Sertifikasi::all();
        return response()->json($data);
    }

    // Simpan data sertifikasi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sertifikasi' => 'required|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'sertifikasi_dari' => 'nullable|string|max:255',
        ]);

        $data = Sertifikasi::create($validated);
        return response()->json($data, 201);
    }

    // Tampilkan satu data sertifikasi
    public function show($id)
    {
        $data = Sertifikasi::findOrFail($id);
        return response()->json($data);
    }

    // Update data sertifikasi
    public function update(Request $request, $id)
    {
        $data = Sertifikasi::findOrFail($id);

        $validated = $request->validate([
            'nama_sertifikasi' => 'sometimes|required|string|max:255',
            'tanggal_berlaku' => 'nullable|date',
            'sertifikasi_dari' => 'nullable|string|max:255',
        ]);

        $data->update($validated);
        return response()->json($data);
    }

    // Hapus data sertifikasi
    public function destroy($id)
    {
        $data = Sertifikasi::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Sertifikasi berhasil dihapus.']);
    }
}
