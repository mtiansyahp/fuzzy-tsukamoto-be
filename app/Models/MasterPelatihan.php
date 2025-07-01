<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPelatihan extends Model
{
    protected $table = 'master_pelatihan';
    protected $primaryKey = 'id_pelatihan';
    public $timestamps = false;

    protected $fillable = ['kode_pelatihan', 'nama_pelatihan'];
}
