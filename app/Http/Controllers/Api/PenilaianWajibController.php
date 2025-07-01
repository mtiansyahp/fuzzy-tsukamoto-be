<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenilaianWajib;
use Illuminate\Http\Request;

class PenilaianWajibController extends Controller
{
    // Tampilkan semua data penilaian wajib
    public function index()
    {
        $data = PenilaianWajib::with(['penilaian', 'pelatihan'])->get();
        return response()->json($data);
    }

    // Simpan data penilaian wajib
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penilaian_id' => 'required|exists:penilaian,id_penilaian',
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
        ]);

        // Cegah duplikat (opsional)
        $exists = PenilaianWajib::where('penilaian_id', $validated['penilaian_id'])
            ->where('pelatihan_id', $validated['pelatihan_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data penilaian wajib ini sudah ada.'
            ], 422);
        }

        $data = PenilaianWajib::create($validated);
        return response()->json($data->load(['penilaian', 'pelatihan']), 201);
    }

    // Tampilkan satu data
    public function show($id)
    {
        $data = PenilaianWajib::with(['penilaian', 'pelatihan'])->findOrFail($id);
        return response()->json($data);
    }

    // Perbarui data
    public function update(Request $request, $id)
    {
        $data = PenilaianWajib::findOrFail($id);

        $validated = $request->validate([
            'penilaian_id' => 'sometimes|required|exists:penilaian,id_penilaian',
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
        ]);

        $data->update($validated);
        return response()->json($data->load(['penilaian', 'pelatihan']));
    }

    // Hapus data
    public function destroy($id)
    {
        $data = PenilaianWajib::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Penilaian wajib berhasil dihapus.']);
    }
}
