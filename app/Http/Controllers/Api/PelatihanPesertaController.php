<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PelatihanPeserta;
use Illuminate\Http\Request;

class PelatihanPesertaController extends Controller
{
    // Tampilkan semua peserta pelatihan, bisa difilter berdasarkan pelatihan_id
    public function index(Request $request)
    {
        $query = PelatihanPeserta::with(['pegawai', 'pelatihan']);

        if ($request->has('pelatihan_id')) {
            $query->where('pelatihan_id', $request->pelatihan_id);
        }

        return response()->json($query->get());
    }

    // Tambahkan peserta ke pelatihan (hindari duplikat)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id_pelatihan',
            'peserta_id' => 'required|exists:pegawai,id',
        ]);

        $exists = PelatihanPeserta::where('pelatihan_id', $validated['pelatihan_id'])
            ->where('peserta_id', $validated['peserta_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Peserta sudah terdaftar di pelatihan ini.'
            ], 422);
        }

        $data = PelatihanPeserta::create($validated);

        return response()->json($data->load(['pegawai', 'pelatihan']), 201);
    }

    // Tampilkan detail peserta
    public function show($id)
    {
        $data = PelatihanPeserta::with(['pegawai', 'pelatihan'])->findOrFail($id);
        return response()->json($data);
    }

    // Perbarui data peserta (cek duplikasi saat update)
    public function update(Request $request, $id)
    {
        $data = PelatihanPeserta::findOrFail($id);

        $validated = $request->validate([
            'pelatihan_id' => 'sometimes|required|exists:pelatihan,id_pelatihan',
            'peserta_id' => 'sometimes|required|exists:pegawai,id',
        ]);

        if (isset($validated['pelatihan_id']) && isset($validated['peserta_id'])) {
            $exists = PelatihanPeserta::where('pelatihan_id', $validated['pelatihan_id'])
                ->where('peserta_id', $validated['peserta_id'])
                ->where('id', '!=', $data->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Peserta sudah terdaftar di pelatihan ini.'
                ], 422);
            }
        }

        $data->update($validated);

        return response()->json($data->load(['pegawai', 'pelatihan']));
    }

    // Hapus peserta dari pelatihan
    public function destroy($id)
    {
        $data = PelatihanPeserta::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Peserta berhasil dihapus dari pelatihan.']);
    }
}
