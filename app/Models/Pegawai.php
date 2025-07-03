<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $primaryKey = 'id'; // sesuai kolom id di DB kamu
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string'; // karena kamu pakai P001, dst

    protected $fillable = [
        'id', // ← tambahkan ini agar bisa diisi manual
        'nama',
        'email',
        'password',
        'role',
        'jurusan_id',
        'sertifikasi_id',
        'posisi_id',
        'umur',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id_jurusan');
    }
    public function sertifikasi()
    {
        return $this->belongsTo(Sertifikasi::class, 'sertifikasi_id', 'id_sertifikasi');
    }


    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id_posisi');
    }


    public function logPenilaian()
    {
        return $this->hasMany(LogPenilaian::class, 'id_user');
    }
}
