<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalLapangan extends Model
{
    protected $table = 'jadwal_lapangan';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_lapangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan');
    }

    public function getDurasiAttribute()
    {
        $jamMulai = strtotime($this->jam_mulai);
        $jamSelesai = strtotime($this->jam_selesai);
        $durasi = ($jamSelesai - $jamMulai) / 3600; // Menghitung durasi dalam jam
        return $durasi;
    }
}
