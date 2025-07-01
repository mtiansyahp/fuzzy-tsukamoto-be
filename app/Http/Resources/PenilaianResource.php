<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenilaianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_penilaian' => $this->id_penilaian,
            'uuid' => $this->uuid ?? null,
            'pelatihan_id' => $this->pelatihan_id,
            'rumus_penilaian' => $this->rumus_penilaian,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
