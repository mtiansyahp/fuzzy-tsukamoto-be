<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Rina Fauziah',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-07-15',
                'pendidikan' => 'D3',
                'jurusan' => 'Keperawatan',
                'jabatan' => 'Perawat Mahir'
            ],
            [
                'nama' => 'Siti Aminah',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1992-03-10',
                'pendidikan' => 'S1',
                'jurusan' => 'Farmasi',
                'jabatan' => 'Perawat Ahli Pertama'
            ],
            // Tambahkan lebih banyak data pegawai di sini jika perlu
        ];

        foreach ($data as $row) {
            $id = $this->generateKodePegawai();
            $umur = date('Y') - date('Y', strtotime($row['tanggal_lahir']));
            $email = Str::slug($row['nama'], '.') . '@rs.com';

            $pendidikan = $this->singkatLulusan($row['pendidikan']);
            $jurusan = ucfirst(strtolower($row['jurusan']));
            $jabatan = ucfirst(strtolower($row['jabatan']));

            $jurusan_id = $this->generateJurusanId($jurusan);
            $posisi_id = $this->generatePosisiId($jabatan);
            $jabatan_kode = $this->mapJabatan($jabatan);

            // Insert Pegawai
            DB::table('pegawai')->insert([
                'id' => $id,
                'email' => $email,
                'password' => Hash::make('perawat123'),
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
                'no_telepon' => null,
                'jabatan' => $jabatan_kode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Pelatihan Dasar
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

            // Pelatihan Lanjutan (minimal 3)
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
        $lastId = DB::table('pegawai')->selectRaw("MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)) as max_id")->value('max_id');
        $nextId = $lastId ? $lastId + 1 : 1;
        return 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    private function generateJurusanId($jurusan)
    {
        return 'J-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $jurusan), 0, 3));
    }

    private function generatePosisiId($jabatan)
    {
        return strtoupper('POS-' . substr(md5($jabatan), 0, 6));
    }

    private function mapJabatan($jabatan)
    {
        $map = [
            'Perawat Penyelia' => 'PRP',
            'Perawat Ahli Pertama' => 'PRAP',
            'Perawat Ahli' => 'PRA',
            'Perawat Terampil' => 'PRT',
            'Perawat Mahir' => 'PRM',
        ];
        return $map[$jabatan] ?? 'PR-UNK';
    }

    private function singkatLulusan($text)
    {
        $map = [
            'diploma iii' => 'D3',
            'diploma iv' => 'D4',
            'profesi ners' => 'NERS',
            'profesi' => 'NERS',
            'sarjana (s1)' => 'S1',
            's1' => 'S1',
            'sma' => 'SMA',
            'smk' => 'SMK',
            'd.iii' => 'D3',
        ];

        $text = strtolower(trim($text));
        foreach ($map as $key => $val) {
            if (str_contains($text, $key)) {
                return $val;
            }
        }

        return strtoupper(substr($text, 0, 10));
    }
}
