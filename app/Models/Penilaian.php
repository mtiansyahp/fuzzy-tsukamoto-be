<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';
    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'pelatihan_id',
        'rumus_penilaian'
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

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function logs()
    {
        return $this->hasMany(LogPenilaian::class, 'id_penilaian');
    }
}
