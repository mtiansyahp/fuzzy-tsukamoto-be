<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanMaxUmurResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pelatihan_id' => $this->pelatihan_id,
            'max_umur' => $this->max_umur,
        ];
    }
}
