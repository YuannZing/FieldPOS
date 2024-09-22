<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLapangan extends Model
{
    use HasFactory;

    protected $table = 'kategori_lapangan';
    protected $primaryKey = 'id_kategori_lapangan';
    protected $guarded = [];
}
