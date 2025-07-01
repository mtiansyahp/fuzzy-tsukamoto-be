<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    // Tampilkan semua data jurusan
    public function index()
    {
        $jurusan = Jurusan::all();
        return response()->json($jurusan);
    }

    // Simpan jurusan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kampus_jurusan' => 'required|string|max:255',
            'lulusan_terakhir' => 'nullable|string|max:255',
            'lulusan_tahun' => 'nullable|integer',
        ]);

        $jurusan = Jurusan::create($validated);

        return response()->json($jurusan, 201);
    }

    // Tampilkan detail jurusan berdasarkan ID
    public function show($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return response()->json($jurusan);
    }

    // Perbarui data jurusan
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'nama_jurusan' => 'sometimes|required|string|max:255',
            'kampus_jurusan' => 'sometimes|required|string|max:255',
            'lulusan_terakhir' => 'nullable|string|max:255',
            'lulusan_tahun' => 'nullable|integer',
        ]);

        $jurusan->update($validated);

        return response()->json($jurusan);
    }

    // Hapus data jurusan
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return response()->json(['message' => 'Jurusan deleted successfully.']);
    }
}
