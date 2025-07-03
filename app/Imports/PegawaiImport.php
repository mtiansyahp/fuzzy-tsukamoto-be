<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class PegawaiImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            // Skip jika tidak ada nama
            if (!isset($row[0]) || empty(trim($row[0]))) {
                Log::warning("Baris $index dilewati: Nama kosong.");
                continue;
            }

            // Bersihkan dan siapkan data
            $nama = trim($row[0] ?? '');
            $tempat_lahir = trim($row[1] ?? '');
            $tanggal_excel = $row[2] ?? null;

            try {
                // Validasi tanggal lahir
                if (!is_numeric($tanggal_excel)) {
                    Log::warning("Baris $index dilewati: Tanggal lahir tidak valid.");
                    continue;
                }

                $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal_excel)->format('Y-m-d');
                $umur = date('Y') - date('Y', strtotime($tanggal_lahir));
                $pendidikan = strtoupper(trim($row[4] ?? ''));
                $jurusan = ucfirst(strtolower(trim($row[5] ?? '')));
                $jabatan = ucfirst(strtolower(trim($row[6] ?? '')));

                // Skip jika data penting kosong
                if (!$pendidikan || !$jurusan || !$jabatan) {
                    Log::warning("Baris $index dilewati: Kolom penting kosong.");
                    continue;
                }

                // Generate email unik
                $email = Str::slug($nama, '.') . '@rs.com';

                // === Insert Jurusan
                $jurusan_id = $this->mapJurusan($jurusan);
                DB::table('jurusan')->updateOrInsert(
                    ['id_jurusan' => $jurusan_id],
                    [
                        'nama_jurusan' => $jurusan,
                        'kampus_jurusan' => 'Imported',
                        'lulusan_terakhir' => $pendidikan,
                        'lulusan_tahun' => 2018,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // === Sertifikasi default
                DB::table('sertifikasi')->updateOrInsert(
                    ['id_sertifikasi' => 'S01'],
                    [
                        'nama_sertifikasi' => 'Sertifikasi Perawat Profesional',
                        'tanggal_berlaku' => '2022-01-01',
                        'sertifikasi_dari' => 'BNPSI',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // === Posisi dari jabatan
                $posisi_id = $this->mapPosisi($jabatan);
                DB::table('posisi')->updateOrInsert(
                    ['id_posisi' => $posisi_id],
                    [
                        'nama_posisi' => $jabatan,
                        'lama_menjabat' => rand(1, 5),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // === Pegawai
                $id = $this->generateKodePegawai();
                Pegawai::create([
                    'id' => $id,
                    'email' => $email,
                    'password' => Hash::make('perawat123'),
                    'role' => 'pegawai',
                    'nama' => $nama,
                    'jurusan_id' => $jurusan_id,
                    'pendidikan_terakhir' => $pendidikan,
                    'sertifikasi_id' => 'S01',
                    'posisi_id' => $posisi_id,
                    'umur' => $umur,
                    'nilai' => null,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'no_telepon' => null,
                    'jabatan' => $this->mapJabatan($jabatan),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // === Pelatihan Dasar
                foreach (['B1', 'B2', 'B3', 'B4', 'B5'] as $kode) {
                    DB::table('pegawai_pelatihan_riwayat')->insert([
                        'pegawai_id' => $id,
                        'kode_pelatihan' => $kode,
                        'sumber' => 'internal',
                        'status' => 'lulus',
                        'tanggal_ikut' => now()->subDays(rand(60, 180)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // === Pelatihan Lanjutan
                $lanjutan = collect(['A1', 'A2', 'A3', 'A4', 'A5'])->shuffle()->take(rand(3, 5));
                foreach ($lanjutan as $kode) {
                    DB::table('pegawai_pelatihan_riwayat')->insert([
                        'pegawai_id' => $id,
                        'kode_pelatihan' => $kode,
                        'sumber' => 'eksternal',
                        'status' => 'lulus',
                        'tanggal_ikut' => now()->subDays(rand(30, 90)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Baris $index gagal: " . $e->getMessage());
                continue;
            }
        }
    }

    private function generateKodePegawai()
    {
        $lastId = DB::table('pegawai')
            ->selectRaw("MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)) as max_id")
            ->value('max_id');

        $nextId = $lastId ? $lastId + 1 : 1;
        return 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    private function mapJabatan($jabatan)
    {
        $map = [
            'perawat penyelia' => 'PRP',
            'perawat ahli pertama' => 'PRAP',
            'perawat ahli' => 'PRA',
            'perawat terampil' => 'PRT',
            'perawat mahir' => 'PRM',
        ];
        return $map[strtolower(trim($jabatan))] ?? 'PR-UNK';
    }

    private function mapJurusan($jurusan)
    {
        $slug = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $jurusan), 0, 3));
        return 'J-' . $slug;
    }

    private function mapPosisi($jabatan)
    {
        return strtoupper('POS-' . substr(md5(strtolower($jabatan)), 0, 6));
    }
}
