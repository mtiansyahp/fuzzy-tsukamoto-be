<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanPendidikan extends Model
{
    protected $table = 'pelatihan_pendidikan';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'pendidikan_terakhir'];
}
