<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanJurusan;
use Illuminate\Http\Request;

class PelatihanJurusanController extends Controller
{
    // Tampilkan semua relasi pelatihan-jurusan
    public function index()
    {
        $data = PelatihanJurusan::all();
        return response()->json($data);
    }

    // Simpan relasi pelatihan-jurusan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'jurusan_id' => 'required|exists:jurusan,id_jurusan',
        ]);

        $relasi = PelatihanJurusan::create($validated);

        return response()->json($relasi, 201);
    }

    // Tampilkan detail relasi berdasarkan ID (jika ada ID unik)
    public function show($id)
    {
        $relasi = PelatihanJurusan::findOrFail($id);
        return response()->json($relasi);
    }

    // Perbarui relasi pelatihan-jurusan (jika perlu)
    public function update(Request $request, $id)
    {
        $relasi = PelatihanJurusan::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'jurusan_id' => 'sometimes|required|exists:jurusan,id_jurusan',
        ]);

        $relasi->update($validated);

        return response()->json($relasi);
    }

    // Hapus relasi pelatihan-jurusan
    public function destroy($id)
    {
        $relasi = PelatihanJurusan::findOrFail($id);
        $relasi->delete();

        return response()->json(['message' => 'Relasi pelatihan-jurusan berhasil dihapus.']);
    }
}
