<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FuzzyController extends Controller
{
    private function loadData()
    {
        $json = Storage::disk('local')->get('data/db.json');
        $data = json_decode($json, true);

        if (!$data || !is_array($data)) {
            abort(500, 'Data JSON tidak valid atau tidak bisa dibaca.');
        }

        return $data;
    }

    public function getAllPenilaian()
    {
        $data = $this->loadData();
        return response()->json($data['penilaian'] ?? []);
    }

    public function getPenilaianDetail($id)
    {
        $data = $this->loadData();

        $penilaian = collect($data['penilaian'] ?? [])->firstWhere('id_penilaian', $id);
        if (!$penilaian) return response()->json(['message' => 'Penilaian tidak ditemukan'], 404);

        $pesertaList = collect($data['pegawai'])->whereIn('id', $penilaian['list_peserta'])->values();
        $pelatihan = collect($data['pelatihan'])->firstWhere('id_pelatihan', $penilaian['pelatihan_id']);

        $hasilFuzzy = $this->hitungFuzzy(
            $pesertaList->toArray(),
            $pelatihan,
            $data['jurusan'],
            $data['sertifikasi'],
            $data['posisi']
        );

        return response()->json([
            'penilaian' => $penilaian,
            'perhitungan_fuzzy' => $hasilFuzzy,
        ]);
    }


    public function getLogPenilaianByUser($userId)
    {
        $data = $this->loadData();

        $log = collect($data['log_penilaian'] ?? [])
            ->where('id_user', $userId)
            ->values();

        if ($log->isEmpty()) {
            return response()->json(['message' => 'Log penilaian tidak ditemukan'], 404);
        }

        return response()->json($log);
    }
    private function hitungFuzzyWithLog(array $peserta, array $pelatihan): array
    {
        $logResults = [];

        foreach ($peserta as $p) {
            $idLog = 'LP' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);

            $bCount = collect($p['pelatihan_diikuti'])->filter(fn($v) => str_starts_with($v, 'b'))->count();
            $aCount = collect($p['pelatihan_diikuti'])->filter(fn($v) => str_starts_with($v, 'a'))->count();

            $T1 = $bCount / 5;
            $T2 = $aCount / 5;

            $jurusanOk = in_array($p['jurusan_id'], $pelatihan['syarat_jurusan']) ? 1 : 0;
            $posisiOk = isset($pelatihan['syarat_posisi']) && in_array($p['posisi_id'], $pelatihan['syarat_posisi']) ? 1 : 0;
            $umurOk = in_array($p['umur'], $pelatihan['syarat_max_umur']) ? 1 : 0;
            $pendOk = in_array($p['pendidikan_terakhir'], $pelatihan['syarat_pendidikan_terakhir']) ? 1 : 0;
            $sertOk = in_array($p['sertifikasi_id'], $pelatihan['syarat_sertifikasi']) ? 1 : 0;

            $total = ($T1 + $T2 + $posisiOk + $jurusanOk + $umurOk + $pendOk + $sertOk) / 7 * 100;

            $logResults[] = [
                'id' => $idLog,
                'id_penilaian' => $pelatihan['id_pelatihan'],
                'id_user' => $p['id'],
                'step_log' => [
                    'T1' => ['b_count' => $bCount, 'result' => round($T1, 2)],
                    'T2' => ['a_count' => $aCount, 'result' => round($T2, 2)],
                    'jurusan' => [
                        'expected' => $pelatihan['syarat_jurusan'],
                        'actual' => $p['jurusan_id'],
                        'result' => $jurusanOk
                    ],
                    'posisi' => [
                        'expected' => $pelatihan['syarat_posisi'] ?? [],
                        'actual' => $p['posisi_id'],
                        'result' => $posisiOk
                    ],
                    'umur' => [
                        'expected' => $pelatihan['syarat_max_umur'],
                        'actual' => $p['umur'],
                        'result' => $umurOk
                    ],
                    'pendidikan' => [
                        'expected' => $pelatihan['syarat_pendidikan_terakhir'],
                        'actual' => $p['pendidikan_terakhir'],
                        'result' => $pendOk
                    ],
                    'sertifikasi' => [
                        'expected' => $pelatihan['syarat_sertifikasi'],
                        'actual' => $p['sertifikasi_id'],
                        'result' => $sertOk
                    ]
                ],
                'total_nilai' => round($total, 2),
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ];
        }

        return $logResults;
    }
    public function getDetailPenilaianWithLog($id)
    {
        $data = $this->loadData();

        $penilaian = collect($data['penilaian'])->firstWhere('id_penilaian', $id);
        if (!$penilaian) {
            return response()->json(['message' => 'Penilaian tidak ditemukan'], 404);
        }

        $pesertaList = collect($data['pegawai'])->whereIn('id', $penilaian['list_peserta'])->values();
        $pelatihan = collect($data['pelatihan'])->firstWhere('id_pelatihan', $penilaian['pelatihan_id']);

        $log = $this->hitungFuzzyWithLog($pesertaList->toArray(), $pelatihan);

        return response()->json([
            'penilaian_id' => $id,
            'nama_pelatihan' => $pelatihan['nama_pelatihan'] ?? null,
            'log_perhitungan' => $log
        ]);
    }
}
