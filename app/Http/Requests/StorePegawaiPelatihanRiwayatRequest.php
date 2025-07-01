<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiPelatihanRiwayatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pegawai_id' => 'required|string|max:10',
            'kode_pelatihan' => 'required|string|max:10',
            'sumber' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'tanggal_ikut' => 'nullable|date',
        ];
    }
}
