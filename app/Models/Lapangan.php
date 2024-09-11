<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lapangan extends Model
{
    protected $table = 'lapangan';
    protected $primaryKey = 'id_lapangan';

    protected $fillable = [
        'id_kategori_lapangan',
        'nama_lapangan',
        'harga_sewa',
    ];
}
