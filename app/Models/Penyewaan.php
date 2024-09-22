<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'penyewaan';
    protected $primaryKey = 'id_penyewaan';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
=======
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
>>>>>>> b3b79ae42465dcf566e62d81f4ec12d79b17bb41
    }
}
