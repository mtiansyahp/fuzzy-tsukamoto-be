<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiPelatihanRiwayat extends Model
{
    protected $table = 'pegawai_pelatihan_riwayat';
    protected $fillable = [
        'pegawai_id',
        'kode_pelatihan',
        'sumber',
        'status',
        'tanggal_ikut',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }
}
