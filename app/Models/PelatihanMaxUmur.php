<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelatihanMaxUmur extends Model
{
    protected $table = 'pelatihan_max_umur';
    public $timestamps = false;

    protected $fillable = ['pelatihan_id', 'max_umur'];
}
