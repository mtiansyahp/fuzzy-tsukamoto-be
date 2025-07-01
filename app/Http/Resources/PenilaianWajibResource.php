<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenilaianWajibResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'penilaian_id' => $this->penilaian_id,
            'pelatihan_id' => $this->pelatihan_id,
        ];
    }
}
