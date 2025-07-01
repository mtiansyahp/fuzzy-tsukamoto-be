<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosisiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_posisi' => $this->id_posisi,
            'nama_posisi' => $this->nama_posisi,
            'lama_menjabat' => $this->lama_menjabat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
