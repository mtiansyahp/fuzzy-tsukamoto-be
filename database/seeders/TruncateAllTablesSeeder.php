<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateAllTablesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'log_penilaian',
            'penilaian_peserta',
            'penilaian_wajib',
            'pelatihan_syarat',
            'pegawai_pelatihan_riwayat',
            'pelatihan_jurusan',
            'pelatihan_sertifikasi',
            'pelatihan_max_umur',
            'pelatihan_pendidikan',
            'pelatihan_peserta',
            'penilaian',
            'pelatihan',
            'master_pelatihan',
            'pegawai',
            'sertifikasi',
            'jurusan',
            'posisi'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
