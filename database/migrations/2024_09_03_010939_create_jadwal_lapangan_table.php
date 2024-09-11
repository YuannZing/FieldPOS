<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalLapanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_lapangan', function (Blueprint $table) {
            $table->increments('id_jadwal');
            $table->unsignedInteger('id_lapangan'); // Sesuaikan tipe data dengan tabel `lapangan`
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['kosong', 'booking', 'main'])->default('kosong');
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('id_lapangan')
                  ->references('id_lapangan')
                  ->on('lapangan')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_lapangan');
    }
};
