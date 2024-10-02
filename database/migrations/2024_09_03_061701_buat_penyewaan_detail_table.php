<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenyewaanDetailTable extends Migration
{
    public function up()
    {
        Schema::create('penyewaan_detail', function (Blueprint $table) {
            $table->increments('id_penyewaaan_detail');
            $table->integer('id_penyewaan');
            $table->integer('id_lapangan');
            $table->integer('harga_sewa');
            $table->integer('durasi');
            $table->tinyInteger('diskon')->default(0);
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan_lapangan');
    }
};
