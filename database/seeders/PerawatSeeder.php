<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PerawatSeeder extends Seeder
{
    public function run(): void
    {
        $dataPerawat = [
            [
                'nama' => 'Siti Aminah',
                'tanggal_lahir' => '1993-04-20',
                'tempat_lahir' => 'Bandung',
                'pendidikan' => 'D3',
                'jurusan' => 'Keperawatan',
                'jabatan' => 'Perawat Mahir',
                'telepon' => '081234567890',
            ],
            [
                'nama' => 'Rina Fauziah',
                'tanggal_lahir' => '1995-07-12',
                'tempat_lahir' => 'Bogor',
                'pendidikan' => 'S1',
                'jurusan' => 'Farmasi',
                'jabatan' => 'Perawat Ahli Pertama',
                'telepon' => '082345678901',
            ],
        ];

        foreach ($dataPerawat as $row) {
            $id = $this->generateKodePegawai();
            $umur = date('Y') - date('Y', strtotime($row['tanggal_lahir']));
            $jurusan = ucfirst(strtolower($row['jurusan']));
            $jabatan = ucfirst(strtolower($row['jabatan']));
            $pendidikan = strtoupper($row['pendidikan']);

            // === Jurusan
            $jurusan_id = $this->mapJurusan($jurusan);
            DB::table('jurusan')->updateOrInsert(
                ['id_jurusan' => $jurusan_id],
                [
                    'nama_jurusan' => $jurusan,
                    'kampus_jurusan' => 'Auto Imported',
                    'lulusan_terakhir' => $pendidikan,
                    'lulusan_tahun' => 2018,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // === Sertifikasi (1 jenis untuk semua)
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

            // === Posisi
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
            DB::table('pegawai')->insert([
                'id' => $id,
                'email' => Str::slug($row['nama'], '.') . '@rs.com',
                'password' => Hash::make('Admin123'),
                'role' => 'pegawai',
                'nama' => $row['nama'],
                'jurusan_id' => $jurusan_id,
                'pendidikan_terakhir' => $pendidikan,
                'sertifikasi_id' => 'S01',
                'posisi_id' => $posisi_id,
                'umur' => $umur,
                'nilai' => null,
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'no_telepon' => $row['telepon'],
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

            // === Pelatihan Lanjutan Acak (minimal 3)
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
            'perawat mahir' => 'PJ-MHR',
            'perawat ahli' => 'PJ-AHL',
            'perawat terampil' => 'PJ-TRP',
            'perawat ahli pertama' => 'PJ-AHP',
            'perawat penyelia' => 'PJ-PNL',
            'pengelola keperawatan' => 'PJ-PGK',
        ];
        return $map[strtolower(trim($jabatan))] ?? 'PJ-UNK';
    }

    private function mapJurusan($jurusan)
    {
        $slug = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $jurusan), 0, 3));
        return 'J-' . $slug;
    }


    private function mapPosisi($jabatan)
    {
        // Gunakan md5 hash pendek agar unik
        return strtoupper('POS-' . substr(md5(strtolower($jabatan)), 0, 6));
    }
}
