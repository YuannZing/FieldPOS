<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatKategoriLapanganTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_lapangan', function (Blueprint $table) {
            $table->increments('id_kategori_lapangan');
            $table->string('nama_kategori_lapangan');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('kategori_lapangan');
    }
};
