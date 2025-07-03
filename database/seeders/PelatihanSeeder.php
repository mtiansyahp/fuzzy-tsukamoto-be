<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelatihanSeeder extends Seeder
{
    public function run()
    {
        $id = 'PLT01';

        // 1. Tambahkan pelatihan utama
        DB::table('pelatihan')->insert([
            'id_pelatihan' => $id,
            'nama_pelatihan' => 'Pelatihan Gawat Darurat',
            'tanggal_pelatihan' => '2025-08-01',
            'deskripsi_pelatihan' => 'Pelatihan penanganan kegawatdaruratan tingkat dasar dan lanjutan.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Tambahkan semua syarat pelatihan (wajib ikut B1-B5, A1, A3)
        $syarat = ['B1', 'B2', 'B3', 'B4', 'B5', 'A1', 'A3'];
        foreach ($syarat as $kode) {
            DB::table('pelatihan_syarat')->insert([
                'pelatihan_id' => $id,
                'kode_pelatihan' => $kode
            ]);
        }

        // 3. Contoh jurusan yang relevan
        DB::table('pelatihan_jurusan')->insert([
            'pelatihan_id' => $id,
            'jurusan_id' => 'JRS01', // ganti sesuai ID yang ada
        ]);

        // 4. Contoh sertifikasi syarat
        DB::table('pelatihan_sertifikasi')->insert([
            'pelatihan_id' => $id,
            'sertifikasi_id' => 'S01', // ganti sesuai ID yang ada
        ]);

        // 5. Contoh max umur syarat
        DB::table('pelatihan_max_umur')->insert([
            'pelatihan_id' => $id,
            'max_umur' => 35,
        ]);
    }
}
