<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanPeserta extends Model
{
    protected $table = 'pelatihan_peserta';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'peserta_id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peserta_id');
    }
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}
