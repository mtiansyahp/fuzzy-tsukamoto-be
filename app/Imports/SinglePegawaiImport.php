<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\PegawaiPelatihanRiwayat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class SinglePegawaiImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index < 2) continue;


            try {
                $jabatan = strtolower(trim($row[4]));
                if (!str_contains($jabatan, 'perawat')) continue;

                $id = $this->generateKodePegawai();
                $umur = date('Y') - date('Y', strtotime($row[2]));
                $pendidikan = strtoupper(trim($row[5]));
                $jurusan = strtoupper(trim($row[6]));

                $pegawai = Pegawai::create([
                    'id' => $id,
                    'email' => $row[1],
                    'password' => bcrypt('Admin123'),
                    'role' => 'pegawai',
                    'nama' => $row[0],
                    'jurusan_id' => substr($jurusan, 0, 5) ?? 'J01',
                    'pendidikan_terakhir' => $pendidikan,
                    'sertifikasi_id' => 'S01',
                    'posisi_id' => 'P01',
                    'umur' => $umur,
                    'nilai' => null,
                    'tempat_lahir' => $row[3],
                    'tanggal_lahir' => $row[2],
                    'no_telepon' => $row[7],
                    'jabatan' => $row[4],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach (['B1', 'B2', 'B3', 'B4', 'B5'] as $kode) {
                    PegawaiPelatihanRiwayat::create([
                        'pegawai_id' => $pegawai->id,
                        'kode_pelatihan' => $kode,
                        'sumber' => 'internal',
                        'status' => 'lulus',
                        'tanggal_ikut' => now()->subDays(rand(30, 180)),
                    ]);
                }

                $lanjutan = collect(['A1', 'A2', 'A3', 'A4', 'A5'])->shuffle()->take(rand(3, 5));
                foreach ($lanjutan as $kode) {
                    PegawaiPelatihanRiwayat::create([
                        'pegawai_id' => $pegawai->id,
                        'kode_pelatihan' => $kode,
                        'sumber' => 'eksternal',
                        'status' => 'lulus',
                        'tanggal_ikut' => now()->subDays(rand(10, 90)),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Baris $index gagal: " . $e->getMessage());
            }
        }
    }

    private function generateKodePegawai()
    {
        $lastId = Pegawai::selectRaw("MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)) as max_id")->value('max_id');
        $nextId = $lastId ? $lastId + 1 : 1;
        return 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
}
