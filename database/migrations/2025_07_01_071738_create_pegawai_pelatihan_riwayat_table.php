<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiPelatihanRiwayatTable extends Migration
{
    public function up()
    {
        Schema::create('pegawai_pelatihan_riwayat',  function (Blueprint $table) {
            $table->id();
            $table->string('pegawai_id', 10); // tetap string
            $table->string('kode_pelatihan', 10);
            $table->string('sumber')->nullable();
            $table->string('status')->nullable();
            $table->date('tanggal_ikut')->nullable();
            $table->timestamps();

            // Hapus baris foreign key ini:
            // $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('pegawai_pelatihan_riwayat');
    }
}
