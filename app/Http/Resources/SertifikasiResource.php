<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SertifikasiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_sertifikasi' => $this->id_sertifikasi,
            'nama_sertifikasi' => $this->nama_sertifikasi,
            'tanggal_berlaku' => $this->tanggal_berlaku,
            'sertifikasi_dari' => $this->sertifikasi_dari,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
