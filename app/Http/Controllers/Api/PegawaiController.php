<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    // Tampilkan semua pegawai
    public function index()
    {
        $pegawai = Pegawai::with(['jurusan', 'sertifikasi', 'posisi', 'logPenilaian'])->get();
        return response()->json($pegawai);
    }

    // Simpan pegawai baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:pegawai,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'nama' => 'required|string|max:255',
            'jurusan_id' => 'nullable|exists:jurusan,id_jurusan',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'sertifikasi_id' => 'nullable|exists:sertifikasi,id',
            'posisi_id' => 'nullable|exists:posisi,id',
            'umur' => 'nullable|integer',
            'nilai' => 'nullable|numeric',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pegawai = Pegawai::create($validated);

        return response()->json($pegawai, 201);
    }

    // Tampilkan detail pegawai
    public function show($id)
    {
        $pegawai = Pegawai::with(['jurusan', 'sertifikasi', 'posisi', 'logPenilaian'])->findOrFail($id);
        return response()->json($pegawai);
    }

    // Perbarui data pegawai
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'email' => 'sometimes|email|unique:pegawai,email,' . $pegawai->id,
            'password' => 'sometimes|nullable|string|min:6',
            'role' => 'sometimes|string',
            'nama' => 'sometimes|string|max:255',
            'jurusan_id' => 'nullable|exists:jurusan,id_jurusan',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'sertifikasi_id' => 'nullable|exists:sertifikasi,id',
            'posisi_id' => 'nullable|exists:posisi,id',
            'umur' => 'nullable|integer',
            'nilai' => 'nullable|numeric',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pegawai->update($validated);

        return response()->json($pegawai);
    }

    // Hapus pegawai
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return response()->json(['message' => 'Pegawai berhasil dihapus.']);
    }
}
