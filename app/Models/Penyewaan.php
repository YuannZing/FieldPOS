<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'penyewaan';

    // Kolom yang dapat diisi
    protected $fillable = [
        'id_jadwal',
        'nama_penyewa',
        'nomer_penyewa',
    ];

    // Definisi relasi dengan model JadwalLapangan
    public function jadwal()
    {
        return $this->belongsTo(JadwalLapangan::class, 'id_jadwal');
    }
}
