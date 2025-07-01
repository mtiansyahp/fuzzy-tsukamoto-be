<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanSertifikasi extends Model
{
    protected $table = 'pelatihan_sertifikasi';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'sertifikasi_id'];
    
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(Sertifikasi::class, 'sertifikasi_id');
    }
}
