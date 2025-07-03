<?php

namespace App\Imports;

use App\Models\PelatihanPeserta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PelatihanPesertaImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $pelatihanId = trim($row[0] ?? '');
            $pesertaId = trim($row[1] ?? '');

            // Skip jika ada yang kosong
            if (empty($pelatihanId) || empty($pesertaId)) continue;

            // Pastikan pelatihan_id valid
            if (!\App\Models\Pelatihan::where('id_pelatihan', $pelatihanId)->exists()) {
                continue; // abaikan kalau tidak cocok
            }

            PelatihanPeserta::create([
                'pelatihan_id' => $pelatihanId,
                'peserta_id' => $pesertaId,
            ]);
        }
    }
}
