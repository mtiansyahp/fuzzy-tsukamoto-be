<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

class Pelatihan extends Model
{
    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'nama_pelatihan',
        'tanggal_pelatihan',
        'deskripsi_pelatihan'
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         if (empty($model->uuid)) {
    //             $model->uuid = (string) Str::uuid();
    //         }
    //     });
    // }

    public function peserta()
    {
        return $this->hasMany(PelatihanPeserta::class, 'pelatihan_id');
    }

    public function syarat()
    {
        return $this->hasMany(PelatihanSyarat::class, 'pelatihan_id');
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'pelatihan_id');
    }
}
