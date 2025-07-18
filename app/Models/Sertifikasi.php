<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasi';
    protected $primaryKey = 'id_sertifikasi';
    public $incrementing = false; // ✅ karena ID bukan auto-increment
    protected $keyType = 'string'; // ✅ karena ID-nya seperti "S01"
    public $timestamps = true;

    protected $fillable = [
        'nama_sertifikasi',
        'tanggal_berlaku',
        'sertifikasi_dari'
    ];
}
