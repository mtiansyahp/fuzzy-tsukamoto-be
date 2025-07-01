<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPenilaian extends Model
{
    protected $table = 'log_penilaian';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id', // ← TAMBAHKAN INI!
        'id_penilaian',
        'id_user',
        'nilai_T1',
        'nilai_T2',
        'nilai_posisi',
        'nilai_jurusan',
        'nilai_umur',
        'nilai_pendidikan_terakhir',
        'nilai_sertifikasi',
        'total_nilai'
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_user');
    }

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'id_penilaian');
    }
}
