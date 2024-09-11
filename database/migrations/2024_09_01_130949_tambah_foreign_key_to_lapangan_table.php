<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahForeignKeyToLapanganTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->foreign('id_kategori_lapangan')
                  ->references('id_kategori_lapangan') // Nama kolom yang benar di tabel kategori_lapangan
                  ->on('kategori_lapangan') // Tabel referensi
                  ->onUpdate('restrict') // Tindakan saat kolom referensi diperbarui
                  ->onDelete('restrict'); // Tindakan saat kolom referensi dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_lapangan']);
        });
    }
}
