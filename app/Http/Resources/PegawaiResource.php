<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PegawaiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid ?? null,
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $this->role,
            'jabatan' => $this->jabatan,
            'no_telepon' => $this->no_telepon,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'umur' => $this->umur,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'jurusan' => new JurusanResource($this->whenLoaded('jurusan')),
            'sertifikasi' => new SertifikasiResource($this->whenLoaded('sertifikasi')),
            'posisi' => new PosisiResource($this->whenLoaded('posisi')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
