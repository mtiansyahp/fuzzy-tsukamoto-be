<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanJurusan extends Model
{
    protected $table = 'pelatihan_jurusan';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'jurusan_id'];
}
