<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pelatihan;
use App\Models\Penilaian;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel log_penilaian
        DB::table('log_penilaian')->truncate();

        // 1. Jurusan
        DB::table('jurusan')->insert([
            [
                'id_jurusan' => 'J01',
                'nama_jurusan' => 'Keperawatan',
                'kampus_jurusan' => 'Universitas Indonesia',
                'lulusan_terakhir' => 'S1',
                'lulusan_tahun' => 2015,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jurusan' => 'J02',
                'nama_jurusan' => 'Farmasi',
                'kampus_jurusan' => 'Universitas Gadjah Mada',
                'lulusan_terakhir' => 'D3',
                'lulusan_tahun' => 2016,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Sertifikasi
        DB::table('sertifikasi')->insert([
            [
                'id_sertifikasi' => 'S01',
                'nama_sertifikasi' => 'Sertifikasi Perawat Profesional',
                'tanggal_berlaku' => '2022-01-01',
                'sertifikasi_dari' => 'BNPSI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_sertifikasi' => 'S02',
                'nama_sertifikasi' => 'Sertifikasi Apoteker Indonesia',
                'tanggal_berlaku' => '2023-03-01',
                'sertifikasi_dari' => 'IAI',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Posisi
        DB::table('posisi')->insert([
            [
                'id_posisi' => 'P01',
                'nama_posisi' => 'Perawat IGD',
                'lama_menjabat' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_posisi' => 'P02',
                'nama_posisi' => 'Apoteker',
                'lama_menjabat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Pegawai
        DB::table('pegawai')->insert([
            [
                'id' => '1',
                'email' => 'andi@rs.com',
                'password' => bcrypt('andi123'),
                'role' => 'pegawai',
                'nama' => 'Andi Saputra',
                'jurusan_id' => 'J01',
                'pendidikan_terakhir' => 'S1',
                'sertifikasi_id' => 'S01',
                'posisi_id' => 'P01',
                'umur' => 32,
                'nilai' => null,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1992-04-15',
                'no_telepon' => '081234567891',
                'jabatan' => 'Staff Medis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'email' => 'budi@rs.com',
                'password' => bcrypt('budi456'),
                'role' => 'atasan',
                'nama' => 'Budi Hartono',
                'jurusan_id' => 'J02',
                'pendidikan_terakhir' => 'D3',
                'sertifikasi_id' => 'S02',
                'posisi_id' => 'P02',
                'umur' => 29,
                'nilai' => null,
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1995-08-22',
                'no_telepon' => '082112345678',
                'jabatan' => 'Kepala Apotek',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '99',
                'email' => 'admin@rs.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'nama' => 'Admin',
                'jurusan_id' => null,
                'pendidikan_terakhir' => null,
                'sertifikasi_id' => null,
                'posisi_id' => null,
                'umur' => null,
                'nilai' => null,
                'tempat_lahir' => null,
                'tanggal_lahir' => null,
                'no_telepon' => null,
                'jabatan' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 5. Pelatihan
        Pelatihan::create([
            'id_pelatihan' => 'PL01',
            'nama_pelatihan' => 'Pelatihan Dasar IGD',
            'tanggal_pelatihan' => '2024-05-10',
            'deskripsi_pelatihan' => 'Pelatihan dasar untuk tenaga IGD',
            'created_at' => '2024-01-01 08:00:00',
            'updated_at' => '2024-06-16 08:00:00'
        ]);

        //   // 5. Pelatihan
        // DB::table('pelatihan')->insert([
        //     'id_pelatihan' => 'PL01',
        //     'nama_pelatihan' => 'Pelatihan Dasar IGD',
        //     'tanggal_pelatihan' => '2024-05-10',
        //     'deskripsi_pelatihan' => 'Pelatihan dasar untuk tenaga IGD',
        //     'created_at' => '2024-01-01 08:00:00',
        //     'updated_at' => '2024-06-16 08:00:00'
        // ]);

        // 6. Penilaian
        Penilaian::create([
            'id_penilaian' => 'NIL01',
            'pelatihan_id' => 'PL01',
            'rumus_penilaian' => '',
            'created_at' => '2024-01-01 08:00:00',
            'updated_at' => '2024-06-16 08:00:00'
        ]);

        // 7. Pelatihan Peserta
        DB::table('pelatihan_peserta')->insert([
            ['pelatihan_id' => 'PL01', 'peserta_id' => '1'],
            ['pelatihan_id' => 'PL01', 'peserta_id' => '2'],
        ]);

        // 8. Pelatihan Syarat
        DB::table('pelatihan_syarat')->insert([
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'b1'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'b2'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'b3'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'b4'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'b5'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'a1'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'a3'],
            ['pelatihan_id' => 'PL01', 'kode_pelatihan' => 'a5'],
        ]);

        // 9. Pelatihan Jurusan
        DB::table('pelatihan_jurusan')->insert([
            ['pelatihan_id' => 'PL01', 'jurusan_id' => 'J01'],
        ]);

        // 10. Pelatihan Sertifikasi
        DB::table('pelatihan_sertifikasi')->insert([
            ['pelatihan_id' => 'PL01', 'sertifikasi_id' => 'S01'],
        ]);

        // 11. Pelatihan Pendidikan
        DB::table('pelatihan_pendidikan')->insert([
            ['pelatihan_id' => 'PL01', 'pendidikan_terakhir' => 'S1'],
        ]);

        // 12. Pelatihan Umur Maksimal
        DB::table('pelatihan_max_umur')->insert([
            ['pelatihan_id' => 'PL01', 'max_umur' => 32],
        ]);

        // 13. Riwayat Pelatihan Pegawai
        $pegawai_ids = ['1', '2'];
        $dasar = ['B1', 'B2', 'B3', 'B4', 'B5'];
        $lanjutan = ['A1', 'A2', 'A3', 'A4', 'A5'];

        foreach ($pegawai_ids as $id) {
            foreach ($dasar as $kode) {
                DB::table('pegawai_pelatihan_riwayat')->insert([
                    'pegawai_id' => $id,
                    'kode_pelatihan' => $kode,
                    'sumber' => 'internal',
                    'status' => 'lulus',
                    'tanggal_ikut' => now()->subDays(rand(60, 180))
                ]);
            }

            $ikut = collect($lanjutan)->random(rand(1, 5));
            foreach ($ikut as $kode) {
                DB::table('pegawai_pelatihan_riwayat')->insert([
                    'pegawai_id' => $id,
                    'kode_pelatihan' => $kode,
                    'sumber' => 'eksternal',
                    'status' => 'lulus',
                    'tanggal_ikut' => now()->subDays(rand(30, 120))
                ]);
            }
        }
    }
}
