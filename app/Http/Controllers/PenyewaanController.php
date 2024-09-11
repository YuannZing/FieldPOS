<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Http\Request;

class PenyewaanController extends Controller
{
    // Menampilkan daftar penyewaan
    public function index()
    {
        $penyewaan = Penyewaan::with('jadwal')->get();
        return view('penyewaan.index', compact('penyewaan'));
    }

    // Menyimpan penyewaan baru
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_lapangan,id_jadwal',
            'nama_penyewa' => 'required|string|max:255',
            'nomer_penyewa' => 'required|string|max:15', // Sesuaikan dengan tipe data yang baru
        ]);

        Penyewaan::create($request->all());

        return redirect()->route('penyewaan.index')->with('success', 'Penyewaan berhasil ditambahkan');
    }

    // Metode lainnya seperti edit, update, delete, dll.
}
