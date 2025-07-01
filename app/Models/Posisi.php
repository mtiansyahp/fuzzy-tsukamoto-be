<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    protected $table = 'posisi';
    protected $primaryKey = 'id_posisi';
    public $timestamps = true;

    protected $fillable = ['nama_posisi', 'lama_menjabat'];
}
