<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JurusanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_jurusan' => $this->id_jurusan,
            'nama_jurusan' => $this->nama_jurusan,
            'kampus_jurusan' => $this->kampus_jurusan,
            'lulusan_terakhir' => $this->lulusan_terakhir,
            'lulusan_tahun' => $this->lulusan_tahun,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
