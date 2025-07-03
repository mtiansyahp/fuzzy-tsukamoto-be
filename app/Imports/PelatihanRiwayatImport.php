<?php

namespace App\Imports;

use App\Models\PegawaiPelatihanRiwayat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class PelatihanRiwayatImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip header
            if ($index === 0) continue;

            // Validasi minimal kolom yang dibutuhkan
            if (count($row) < 4 || empty($row[0]) || empty($row[1])) {
                Log::warning("Data tidak valid di baris $index: " . json_encode($row));
                continue;
            }

            try {
                PegawaiPelatihanRiwayat::create([
                    'pegawai_id' => trim($row[0]),
                    'kode_pelatihan' => trim($row[1]),
                    'sumber' => trim($row[2]),
                    'status' => trim($row[3]),
                    'tanggal_ikut' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Gagal import baris $index: " . $e->getMessage());
                continue;
            }
        }
    }
}
