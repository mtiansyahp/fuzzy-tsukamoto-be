<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanSertifikasiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pelatihan_id' => $this->pelatihan_id,
            'sertifikasi_id' => $this->sertifikasi_id,
        ];
    }
}
