<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    protected $table = 'posisi';
    protected $primaryKey = 'id_posisi'; // ✅ Ini WAJIB
    public $incrementing = false;        // karena kamu pakai string seperti "POS-060ae9"
    protected $keyType = 'string';

    protected $fillable = ['nama_posisi', 'lama_menjabat'];
}
