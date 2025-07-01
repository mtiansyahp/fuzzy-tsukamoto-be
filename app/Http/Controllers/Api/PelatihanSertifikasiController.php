<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanSertifikasi;
use Illuminate\Http\Request;

class PelatihanSertifikasiController extends Controller
{
    // Tampilkan semua relasi pelatihan-sertifikasi
    public function index()
    {
        $data = PelatihanSertifikasi::all();
        return response()->json($data);
    }

    // Simpan relasi baru pelatihan-sertifikasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'sertifikasi_id' => 'required|exists:sertifikasi,id',
        ]);

        // Cegah duplikasi relasi
        $exists = PelatihanSertifikasi::where('pelatihan_id', $validated['pelatihan_id'])
            ->where('sertifikasi_id', $validated['sertifikasi_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Relasi pelatihan-sertifikasi sudah ada.'], 422);
        }

        $data = PelatihanSertifikasi::create($validated);

        return response()->json($data, 201);
    }

    // Tampilkan relasi berdasarkan ID
    public function show($id)
    {
        $data = PelatihanSertifikasi::findOrFail($id);
        return response()->json($data);
    }

    // Update relasi pelatihan-sertifikasi
    public function update(Request $request, $id)
    {
        $data = PelatihanSertifikasi::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'sertifikasi_id' => 'sometimes|required|exists:sertifikasi,id',
        ]);

        $data->update($validated);

        return response()->json($data);
    }

    // Hapus relasi
    public function destroy($id)
    {
        $data = PelatihanSertifikasi::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Relasi pelatihan-sertifikasi berhasil dihapus.']);
    }
}
