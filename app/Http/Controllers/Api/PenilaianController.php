<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // Tampilkan semua penilaian dengan relasi pelatihan dan logs
    public function index()
    {
        $data = Penilaian::with(['pelatihan', 'logs'])->get();
        return response()->json($data);
    }

    // Simpan penilaian baru (UUID akan dibuat otomatis)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'rumus_penilaian' => 'required|string'
        ]);

        $penilaian = Penilaian::create($validated);
        return response()->json($penilaian->load('pelatihan'), 201);
    }

    // Tampilkan detail penilaian + pelatihan dan log
    public function show($id)
    {
        $data = Penilaian::with(['pelatihan', 'logs'])->findOrFail($id);
        return response()->json($data);
    }

    // Update data penilaian
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'rumus_penilaian' => 'sometimes|required|string',
        ]);

        $penilaian->update($validated);
        return response()->json($penilaian->load('pelatihan'));
    }

    // Hapus data penilaian
    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->delete();

        return response()->json(['message' => 'Penilaian berhasil dihapus.']);
    }
}
