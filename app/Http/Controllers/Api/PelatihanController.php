<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    // Tampilkan semua data pelatihan dengan relasi
    public function index()
    {
        $pelatihan = Pelatihan::with(['peserta', 'syarat', 'penilaian'])->get();
        return response()->json($pelatihan);
    }

    // Simpan pelatihan baru (UUID akan otomatis terisi)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal_pelatihan' => 'required|date',
            'deskripsi_pelatihan' => 'nullable|string'
        ]);

        $pelatihan = Pelatihan::create($validated);

        return response()->json($pelatihan, 201);
    }

    // Tampilkan detail pelatihan berdasarkan ID
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['peserta', 'syarat', 'penilaian'])->findOrFail($id);
        return response()->json($pelatihan);
    }

    // Perbarui data pelatihan
    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $validated = $request->validate([
            'nama_pelatihan' => 'sometimes|required|string|max:255',
            'tanggal_pelatihan' => 'sometimes|required|date',
            'deskripsi_pelatihan' => 'nullable|string'
        ]);

        $pelatihan->update($validated);

        return response()->json($pelatihan);
    }

    // Hapus pelatihan
    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $pelatihan->delete();

        return response()->json(['message' => 'Pelatihan berhasil dihapus.']);
    }
}
