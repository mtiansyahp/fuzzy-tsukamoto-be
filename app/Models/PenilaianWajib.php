<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianWajib extends Model
{
    protected $table = 'penilaian_wajib';
    public $timestamps = false;

    protected $fillable = ['penilaian_id', 'pelatihan_id'];
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}
