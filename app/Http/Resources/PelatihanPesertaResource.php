<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanPesertaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pelatihan_id' => $this->pelatihan_id,
            'peserta_id' => $this->peserta_id,
        ];
    }
}
