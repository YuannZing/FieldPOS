<?php

namespace App\Http\Controllers;

use App\Models\JadwalLapangan;
use Illuminate\Http\Request;

class JadwalLapanganController extends Controller
{
    public function index()
    {
        $jadwal = JadwalLapangan::all();
        return response()->json($jadwal);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_lapangan' => 'required|integer|exists:lapangan,id_lapangan',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|in:kosong,booking,main',
        ]);

        $jadwal = JadwalLapangan::create($validatedData);
        return response()->json(['message' => 'Jadwal berhasil dibuat', 'jadwal' => $jadwal], 201);
    }

    public function show($id)
    {
        $jadwal = JadwalLapangan::findOrFail($id);
        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_lapangan' => 'integer|exists:lapangan,id_lapangan',
            'tanggal' => 'date',
            'jam_mulai' => 'date_format:H:i',
            'jam_selesai' => 'date_format:H:i',
            'status' => 'in:kosong,booking,main',
        ]);

        $jadwal = JadwalLapangan::findOrFail($id);
        $jadwal->update($validatedData);
        return response()->json(['message' => 'Jadwal berhasil diperbarui', 'jadwal' => $jadwal]);
    }

    public function destroy($id)
    {
        $jadwal = JadwalLapangan::findOrFail($id);
        $jadwal->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}
