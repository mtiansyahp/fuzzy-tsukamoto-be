<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\PegawaiPelatihanRiwayat;
use App\Models\PelatihanJurusan;
use App\Models\PelatihanSertifikasi;
use App\Models\PelatihanPendidikan;
use App\Models\LogPenilaian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FuzzyController extends Controller
{
    // Tambahkan ini di dalam class FuzzyController tapi DI LUAR method lainnya
    private function generateKodeLogPenilaian()
    {
        $max = LogPenilaian::selectRaw("MAX(CAST(SUBSTRING(id, 3) AS UNSIGNED)) as max_id")->value('max_id');
        $next = $max ? $max + 1 : 1;
        return 'LP' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function hitungFuzzyTsukamoto($id_peserta)
    {
        // Ambil data peserta + relasi
        // $peserta = Pegawai::with(['jurusan', 'sertifikasi', 'posisi'])->find($id_peserta);
        // if (!$peserta) {
        //     return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        // }
        $peserta = Pegawai::with(['jurusan', 'sertifikasi', 'posisi'])
            ->where('id', $id_peserta)
            ->first();


        // Ambil riwayat pelatihan
        $riwayat = PegawaiPelatihanRiwayat::where('pegawai_id', $id_peserta)->pluck('kode_pelatihan')->toArray();
        $T1 = collect($riwayat)->filter(fn($p) => str_starts_with($p, 'b'))->count() / 5;
        $T2 = collect($riwayat)->filter(fn($p) => str_starts_with($p, 'a'))->count() / 5;

        // Input fuzzy terurut seperti gambar
        $nilai_fuzzy = [
            'T1' => $this->mapFuzzy('Pelatihan', $this->kategoriTingkat($T1)),
            'T2' => $this->mapFuzzy('Pelatihan', $this->kategoriTingkat($T2)),
            'pendidikan' => $this->mapFuzzy('Pendidikan', $this->kategoriTingkatPendidikan($peserta->pendidikan_terakhir)),
            'umur' => $this->mapFuzzy('Umur', $this->kategoriUmur($peserta->tanggal_lahir)),
            'sertifikasi' => $this->mapFuzzy('Sertifikasi', $this->kategoriSertifikasi($peserta->sertifikasi_id)),
            'pelatihan' => $this->mapFuzzy('PelatihanRiwayat', count($riwayat) > 0 ? 'Sudah Pernah Ikut' : 'Tidak Pernah'),
            'jurusan' => $this->mapFuzzy('Jurusan', $this->kategoriJurusan($peserta->jurusan_id)),
            'posisi' => $this->mapFuzzy('Posisi', $this->kategoriPosisi($peserta->posisi_id)),
        ];

        // Hitung skor akhir fuzzy
        $skor = round(array_sum($nilai_fuzzy) / count($nilai_fuzzy) * 100, 2);
        $kategori = $this->klasifikasiSkor($skor);
        // dd($peserta->id);
        // dd($peserta->id); // pastikan bernilai "P001", bukan "0" atau null

        // Simpan ke log penilaian
        LogPenilaian::create([

            'id' => $this->generateKodeLogPenilaian(),
            'id_user' => $peserta->id,
            'id_penilaian' => 'NIL01',
            'nilai_T1' => $nilai_fuzzy['T1'],
            'nilai_T2' => $nilai_fuzzy['T2'],
            'nilai_pendidikan_terakhir' => $nilai_fuzzy['pendidikan'],
            'nilai_umur' => $nilai_fuzzy['umur'],
            'nilai_sertifikasi' => $nilai_fuzzy['sertifikasi'],
            'nilai_jurusan' => $nilai_fuzzy['jurusan'],
            'nilai_posisi' => $nilai_fuzzy['posisi'],
            'total_nilai' => $skor,
        ]);


        return response()->json([
            'peserta' => $peserta->nama,
            'input_fuzzy' => $nilai_fuzzy,
            'skor_akhir' => $skor,
            'kategori' => $kategori
        ]);
    }

    private function mapFuzzy($tipe, $kategori)
    {
        $fuzzyMap = [
            'Pelatihan' => ['Tidak Cocok' => 0.2, 'Kurang Cocok' => 0.4, 'Hampir Cocok' => 0.6, 'Cocok' => 0.8, 'Sangat Cocok' => 1.0],
            'Pendidikan' => ['Tidak Cocok' => 0.2, 'Kurang Cocok' => 0.4, 'Hampir Cocok' => 0.6, 'Cocok' => 0.8, 'Sangat Cocok' => 1.0],
            'Umur' => ['Tidak Dekat' => 0, 'Semakin Dekat' => 0.5, 'Cocok' => 1],
            'Sertifikasi' => ['Sangat Sering' => 0.1, 'Sering' => 0.3, 'Pernah' => 1.0],
            'PelatihanRiwayat' => ['Tidak Pernah' => 0, 'Sudah Pernah Ikut' => 1],
            'Jurusan' => ['Tidak Relevan' => 0, 'Sedikit Relevan' => 0.5, 'Sangat Relevan' => 1],
            'Posisi' => ['Tidak Cocok' => 0, 'Sangat Cocok' => 1]
        ];

        return $fuzzyMap[$tipe][$kategori] ?? 0;
    }

    private function kategoriTingkat($nilai)
    {
        if ($nilai >= 0.8) return 'Sangat Cocok';
        elseif ($nilai >= 0.6) return 'Cocok';
        elseif ($nilai >= 0.4) return 'Hampir Cocok';
        elseif ($nilai >= 0.2) return 'Kurang Cocok';
        else return 'Tidak Cocok';
    }

    private function kategoriTingkatPendidikan($tingkat)
    {
        return match (strtolower($tingkat)) {
            'SMA' => 'Kurang Cocok',
            'D3' => 'Hampir Cocok',
            'S1' => 'Cocok',
            'S2', 'S3' => 'Sangat Cocok',
            default => 'Tidak Cocok',
        };
    }

    private function kategoriUmur($tgl_lahir)
    {
        $umur = Carbon::parse($tgl_lahir)->age;
        return match (true) {
            $umur <= 25 => 'Cocok',
            $umur <= 35 => 'Semakin Dekat',
            default => 'Tidak Dekat'
        };
    }

    private function kategoriSertifikasi($id)
    {
        if (!$id) return 'Tidak Pernah';
        return 'Sering'; // atau cek tanggal berlaku jika ingin lebih akurat
    }

    private function kategoriJurusan($jurusan_id)
    {
        // Cek apakah jurusan termasuk dalam syarat pelatihan tertentu? (sementara: Sangat Relevan)
        return 'Sangat Relevan'; // bisa disesuaikan
    }

    private function kategoriPosisi($posisi_id)
    {
        return $posisi_id ? 'Sangat Cocok' : 'Tidak Cocok';
    }

    private function klasifikasiSkor($skor)
    {
        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 75) return 'Baik';
        if ($skor >= 65) return 'Cukup';
        if ($skor >= 50) return 'Kurang';
        return 'Sangat Kurang';
    }
    public function hitungBatchPeserta($id_penilaian)
    {
        // Ambil semua peserta yang terkait pelatihan tertentu
        $pesertaList = \App\Models\PelatihanPeserta::where('pelatihan_id', $id_penilaian)
            ->with(['pegawai.jurusan', 'pegawai.sertifikasi', 'pegawai.posisi'])
            ->get();

        $results = [];

        foreach ($pesertaList as $pesertaItem) {
            $pegawai = $pesertaItem->pegawai;
            if (!$pegawai) continue;

            $riwayat = \App\Models\PegawaiPelatihanRiwayat::where('pegawai_id', $pegawai->id)
                ->pluck('kode_pelatihan')->toArray();
            $T1 = collect($riwayat)->filter(fn($p) => str_starts_with($p, 'b'))->count() / 5;
            $T2 = collect($riwayat)->filter(fn($p) => str_starts_with($p, 'a'))->count() / 5;

            $nilai_fuzzy = [
                'T1' => $this->mapFuzzy('Pelatihan', $this->kategoriTingkat($T1)),
                'T2' => $this->mapFuzzy('Pelatihan', $this->kategoriTingkat($T2)),
                'pendidikan' => $this->mapFuzzy('Pendidikan', $this->kategoriTingkatPendidikan($pegawai->pendidikan_terakhir)),
                'umur' => $this->mapFuzzy('Umur', $this->kategoriUmur($pegawai->tanggal_lahir)),
                'sertifikasi' => $this->mapFuzzy('Sertifikasi', $this->kategoriSertifikasi($pegawai->sertifikasi_id)),
                'pelatihan' => $this->mapFuzzy('PelatihanRiwayat', count($riwayat) > 0 ? 'Sudah Pernah Ikut' : 'Tidak Pernah'),
                'jurusan' => $this->mapFuzzy('Jurusan', $this->kategoriJurusan($pegawai->jurusan_id)),
                'posisi' => $this->mapFuzzy('Posisi', $this->kategoriPosisi($pegawai->posisi_id)),
            ];

            $skor = round(array_sum($nilai_fuzzy) / count($nilai_fuzzy) * 100, 2);
            $kategori = $this->klasifikasiSkor($skor);

            // Simpan ke log_penilaian
            \App\Models\LogPenilaian::updateOrCreate(
                ['id_user' => $pegawai->id, 'id_penilaian' => $id_penilaian],
                [
                    'nilai_T1' => $nilai_fuzzy['T1'],
                    'nilai_T2' => $nilai_fuzzy['T2'],
                    'nilai_pendidikan_terakhir' => $nilai_fuzzy['pendidikan'],
                    'nilai_umur' => $nilai_fuzzy['umur'],
                    'nilai_sertifikasi' => $nilai_fuzzy['sertifikasi'],
                    'nilai_jurusan' => $nilai_fuzzy['jurusan'],
                    'nilai_posisi' => $nilai_fuzzy['posisi'],
                    'total_nilai' => $skor,
                ]
            );

            $results[] = [
                'id_user' => $pegawai->id,
                'nama' => $pegawai->nama,
                'skor' => $skor,
                'kategori' => $kategori
            ];
        }

        // Urutkan dari skor tertinggi ke terendah
        $sorted = collect($results)->sortByDesc('skor')->values();

        return response()->json([
            'pelatihan_id' => $id_penilaian,
            'total_peserta' => count($sorted),
            'hasil_klasifikasi' => $sorted
        ]);
    }
}
