<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_pelatihan' => $this->id_pelatihan,
            'uuid' => $this->uuid ?? null,
            'nama_pelatihan' => $this->nama_pelatihan,
            'tanggal_pelatihan' => $this->tanggal_pelatihan,
            'deskripsi_pelatihan' => $this->deskripsi_pelatihan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
