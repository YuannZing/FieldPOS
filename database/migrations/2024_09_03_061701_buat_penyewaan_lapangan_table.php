<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenyewaanLapanganTable extends Migration
{
    public function up()
    {
        Schema::create('penyewaan_lapangan', function (Blueprint $table) {
            $table->id('id_penyewaan');
            $table->unsignedInteger('id_jadwal');
            $table->string('nama_penyewa');
            $table->string('nomer_penyewa');
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('id_jadwal')
                  ->references('id_jadwal')
                  ->on('jadwal_lapangan')
                  ->onDelete('restrict');
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
