<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanJurusanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pelatihan_id' => $this->pelatihan_id,
            'jurusan_id' => $this->jurusan_id,
        ];
    }
}
