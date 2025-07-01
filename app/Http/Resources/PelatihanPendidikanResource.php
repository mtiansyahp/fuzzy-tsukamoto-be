<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanPendidikanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pelatihan_id' => $this->pelatihan_id,
            'pendidikan_terakhir' => $this->pendidikan,
        ];
    }
}
