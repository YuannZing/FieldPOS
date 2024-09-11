<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatLapanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lapangan', function (Blueprint $table) {
            $table->increments('id_lapangan'); // Creates an auto-incrementing primary key column
            $table->unsignedInteger('id_kategori_lapangan'); // Assuming it is a foreign key
            $table->string('nama_lapangan');
            $table->integer('harga_sewa');
            $table->timestamps(); // This will create both created_at and updated_at columns with default NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lapangan');
    }
}
