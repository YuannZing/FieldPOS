<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenyewaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->increments('id_penyewaan');
            $table->integer('id_member');        // Relasi ke member yang menyewa
            $table->integer('total_durasi');     // Total durasi penyewaan (jam)
            $table->integer('total_harga');      // Total harga penyewaan
            $table->integer('bayar')->default(0); // Jumlah yang harus dibayar
            $table->integer('diterima')->default(0); // Jumlah uang yang diterima
            $table->integer('id_user');          // Relasi ke user yang memproses transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyewaan');
    }
}
