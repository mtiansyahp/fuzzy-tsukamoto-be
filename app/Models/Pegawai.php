<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    protected $keyType = 'string'; // penting agar ID seperti 'P001' dianggap string
    public $incrementing = false; // karena kamu pakai manual id
    public $timestamps = true;

    protected $fillable = [
        'email',
        'password',
        'role',
        'nama',
        'jurusan_id',
        'pendidikan_terakhir',
        'sertifikasi_id',
        'posisi_id',
        'umur',
        'nilai',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'jabatan'
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function sertifikasi()
    {
        return $this->belongsTo(Sertifikasi::class, 'sertifikasi_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id');
    }

    public function logPenilaian()
    {
        return $this->hasMany(LogPenilaian::class, 'id_user');
    }
}
