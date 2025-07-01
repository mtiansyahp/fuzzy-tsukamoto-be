<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogPenilaian;
use Illuminate\Http\Request;

class LogPenilaianController extends Controller
{
    // Tampilkan semua data log penilaian
    public function index()
    {
        $log = LogPenilaian::with(['pegawai', 'penilaian'])->get();
        return response()->json($log);
    }

    // Simpan log penilaian baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penilaian' => 'required|exists:penilaian,id',
            'id_user' => 'required|exists:pegawai,id',
            'nilai_T1' => 'nullable|numeric',
            'nilai_T2' => 'nullable|numeric',
            'nilai_posisi' => 'nullable|numeric',
            'nilai_jurusan' => 'nullable|numeric',
            'nilai_umur' => 'nullable|numeric',
            'nilai_pendidikan_terakhir' => 'nullable|numeric',
            'nilai_sertifikasi' => 'nullable|numeric',
            'total_nilai' => 'nullable|numeric',
        ]);

        $log = LogPenilaian::create($validated);

        return response()->json($log, 201);
    }

    // Tampilkan detail log berdasarkan ID
    public function show($id)
    {
        $log = LogPenilaian::with(['pegawai', 'penilaian'])->findOrFail($id);
        return response()->json($log);
    }

    // Perbarui log penilaian
    public function update(Request $request, $id)
    {
        $log = LogPenilaian::findOrFail($id);

        $validated = $request->validate([
            'id_penilaian' => 'sometimes|required|exists:penilaian,id',
            'id_user' => 'sometimes|required|exists:pegawai,id',
            'nilai_T1' => 'nullable|numeric',
            'nilai_T2' => 'nullable|numeric',
            'nilai_posisi' => 'nullable|numeric',
            'nilai_jurusan' => 'nullable|numeric',
            'nilai_umur' => 'nullable|numeric',
            'nilai_pendidikan_terakhir' => 'nullable|numeric',
            'nilai_sertifikasi' => 'nullable|numeric',
            'total_nilai' => 'nullable|numeric',
        ]);

        $log->update($validated);

        return response()->json($log);
    }

    // Hapus log penilaian
    public function destroy($id)
    {
        $log = LogPenilaian::findOrFail($id);
        $log->delete();

        return response()->json(['message' => 'Log penilaian berhasil dihapus.']);
    }
}
