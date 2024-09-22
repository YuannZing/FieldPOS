<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyewaanDetail extends Model
{
    use HasFactory;

    protected $table = 'penyewaan_detail';
    protected $primaryKey = 'id_penyewaan_detail';
    protected $guarded = [];

    public function lapangan()
    {
        return $this->hasOne(Lapangan::class, 'id_lapangan', 'id_lapangan');
    }
}
