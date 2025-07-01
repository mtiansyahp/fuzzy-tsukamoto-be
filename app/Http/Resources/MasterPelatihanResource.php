<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MasterPelatihanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_pelatihan' => $this->id_pelatihan,
            'kode_pelatihan' => $this->kode_pelatihan,
            'nama_pelatihan' => $this->nama_pelatihan,
        ];
    }
}
