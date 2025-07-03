<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        // Generate ID pegawai otomatis
        $request->merge([
            'id' => $this->generateKodePegawai()
        ]);
        // dd($request->all());

        $validated = $request->validate([
            'id' => 'required|string|unique:pegawai,id',
            'email' => 'required|email|unique:pegawai,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
            'nama' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusan,id_jurusan',
            // 'pendidikan_terakhir' => 'required|string|max:255',
            'sertifikasi_id' => 'required|exists:sertifikasi,id_sertifikasi',
            'posisi_id' => 'required|exists:posisi,id_posisi',
            'umur' => 'nullable|integer',
            'nilai' => 'nullable|numeric',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'b1' => 'nullable|boolean',
            'b2' => 'nullable|boolean',
            'b3' => 'nullable|boolean',
            'b4' => 'nullable|boolean',
            'b5' => 'nullable|boolean',
            'a1' => 'nullable|boolean',
            'a2' => 'nullable|boolean',
            'a3' => 'nullable|boolean',
            'a4' => 'nullable|boolean',
            'a5' => 'nullable|boolean',
        ]);

        if (!isset($validated['umur']) && $request->filled('tanggal_lahir')) {
            $validated['umur'] = date('Y') - date('Y', strtotime($request->tanggal_lahir));
        }

        $validated['password'] = Hash::make($validated['password']);

        DB::beginTransaction();
        try {
            $pegawai = Pegawai::create($validated);

            $this->savePelatihanRiwayat($pegawai->id, $request);

            DB::commit();
            return response()->json($pegawai, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            'sertifikasi_id' => 'required|exists:sertifikasi,id_sertifikasi',
            'posisi_id' => 'nullable|exists:posisi,id',
            'umur' => 'nullable|integer',
            'nilai' => 'nullable|numeric',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'b1' => 'nullable|boolean',
            'b2' => 'nullable|boolean',
            'b3' => 'nullable|boolean',
            'b4' => 'nullable|boolean',
            'b5' => 'nullable|boolean',
            'a1' => 'nullable|boolean',
            'a2' => 'nullable|boolean',
            'a3' => 'nullable|boolean',
            'a4' => 'nullable|boolean',
            'a5' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pegawai->update($validated);

        $this->savePelatihanRiwayat($pegawai->id, $request);

        return response()->json($pegawai);
    }

    // Hapus pegawai
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return response()->json(['message' => 'Pegawai berhasil dihapus.']);
    }

    // Helper: generate ID otomatis
    private function generateKodePegawai()
    {
        $lastId = DB::table('pegawai')->selectRaw("MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)) as max_id")->value('max_id');
        $nextId = $lastId ? $lastId + 1 : 1;
        return 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    // Helper: simpan pelatihan riwayat
    private function savePelatihanRiwayat($pegawaiId, Request $request)
    {
        $kodePelatihanMap = [
            'b1' => 'B1',
            'b2' => 'B2',
            'b3' => 'B3',
            'b4' => 'B4',
            'b5' => 'B5',
            'a1' => 'A1',
            'a2' => 'A2',
            'a3' => 'A3',
            'a4' => 'A4',
            'a5' => 'A5',
        ];

        foreach ($kodePelatihanMap as $inputKey => $kodePelatihan) {
            if ($request->boolean($inputKey)) {
                $exists = DB::table('pegawai_pelatihan_riwayat')
                    ->where('pegawai_id', $pegawaiId)
                    ->where('kode_pelatihan', $kodePelatihan)
                    ->exists();

                if (!$exists) {
                    DB::table('pegawai_pelatihan_riwayat')->insert([
                        'pegawai_id' => $pegawaiId,
                        'kode_pelatihan' => $kodePelatihan,
                        'sumber' => str_starts_with($kodePelatihan, 'A') ? 'eksternal' : 'internal',
                        'status' => 'lulus',
                        'tanggal_ikut' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
