<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';

    // 👇 Tambahkan baris-baris penting ini:
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = ['id_jurusan', 'nama_jurusan', 'kampus_jurusan', 'lulusan_terakhir', 'lulusan_tahun'];


    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'jurusan_id');
    }
}
