<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanSyarat extends Model
{
    protected $table = 'pelatihan_syarat';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'kode_pelatihan'];
    
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}
