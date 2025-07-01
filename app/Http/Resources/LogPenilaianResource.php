<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogPenilaianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'id_user' => $this->id_user,
            'id_penilaian' => $this->id_penilaian,
            'nilai_T1' => $this->nilai_T1,
            'nilai_T2' => $this->nilai_T2,
            'nilai_posisi' => $this->nilai_posisi,
            'nilai_jurusan' => $this->nilai_jurusan,
            'nilai_umur' => $this->nilai_umur,
            'nilai_pendidikan_terakhir' => $this->nilai_pendidikan_terakhir,
            'nilai_sertifikasi' => $this->nilai_sertifikasi,
            'total_nilai' => $this->total_nilai,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
