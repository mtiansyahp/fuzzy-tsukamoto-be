<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('database/seeders/excel/Data Final (2).xlsx');
        $sheets = ['IGD', 'ICU'];
        $jurusanSet = [];
        $jabatanSet = [];

        foreach ($sheets as $sheet) {
            $data = Excel::toArray([], $path)[$sheetIndex = $sheet === 'IGD' ? 0 : 1];

            foreach ($data as $index => $row) {
                if ($index === 0 || count($row) < 7) continue;

                $pendidikan = strtoupper(trim($row[4]));
                $jurusan = ucfirst(strtolower(trim($row[5])));
                $jabatan = ucfirst(strtolower(trim($row[6])));

                $jurusanSet[$jurusan] = $pendidikan;
                $jabatanSet[$jabatan] = true;
            }
        }

        // === Jurusan
        foreach ($jurusanSet as $jurusan => $pendidikan) {
            $id = 'J-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $jurusan), 0, 3));
            DB::table('jurusan')->updateOrInsert(
                ['id_jurusan' => $id],
                [
                    'nama_jurusan' => $jurusan,
                    'kampus_jurusan' => 'Imported',
                    'lulusan_terakhir' => $this->singkatLulusan($pendidikan),
                    'lulusan_tahun' => 2018,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // === Posisi dari Jabatan
        foreach (array_keys($jabatanSet) as $jabatan) {
            if (!str_contains(strtolower($jabatan), 'perawat')) continue;

            $id = 'POS-' . substr(md5($jabatan), 0, 6);
            DB::table('posisi')->updateOrInsert(
                ['id_posisi' => $id],
                [
                    'nama_posisi' => $jabatan,
                    'lama_menjabat' => rand(1, 5),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }


        // === Sertifikasi (default 1)
        DB::table('sertifikasi')->updateOrInsert(
            ['id_sertifikasi' => 'S01'],
            [
                'nama_sertifikasi' => 'Sertifikasi Perawat Profesional',
                'tanggal_berlaku' => '2022-01-01',
                'sertifikasi_dari' => 'BNPSI',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
    private function singkatLulusan($text)
    {
        $map = [
            'diploma iii' => 'D3',
            'diploma iv' => 'D4',
            'diploma ii' => 'D2',
            'diploma i' => 'D1',
            'profesi ners' => 'NERS',
            'profesi' => 'NERS',
            'sarjana (s1)' => 'S1',
            'sarjana' => 'S1',
            's.1' => 'S1',
            'sma' => 'SMA',
            'smk' => 'SMK',
            'd.iii keperawatan' => 'D3',
            'd.iii' => 'D3',
            'd.ii' => 'D2',
            'd.iv' => 'D4',
        ];

        $text = strtolower(trim($text));
        foreach ($map as $key => $val) {
            if (str_contains($text, $key)) {
                return $val;
            }
        }

        return strtoupper(substr($text, 0, 10)); // fallback pendekkan maksimal 10 karakter
    }
}
