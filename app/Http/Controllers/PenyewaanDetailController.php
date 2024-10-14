<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use App\Models\Lapangan;
// use App\Models\Penyewaan;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use App\Models\PenyewaanDetail;
use Illuminate\Support\Facades\Log;

class PenyewaanDetailController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::orderBy('nama_lapangan')->get(); // Daftar lapangan
        $member = Member::orderBy('nama')->get(); // Daftar member
        $diskon = Setting::first()->diskon ?? 0; // Diskon jika ada

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penyewaan = session('id_penyewaan')) {
            $penyewaan = Penyewaan::find($id_penyewaan);
            $memberSelected = $penyewaan->member ?? new Member();

            return view('penyewaan_detail.index', compact('lapangan', 'member', 'diskon', 'id_penyewaan', 'penyewaan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi_penyewaan.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = PenyewaanDetail::with('lapangan')
            ->where('id_penyewaan', $id)
            ->get();
    
        Log::info("Request for penyewaan data with ID: $id");
    
        $data = array();
        $total = 0;
        $total_durasi = 0; // untuk menyimpan total durasi sewa
    
        foreach ($detail as $item) {
            $row = array();
            $row['nama_lapangan'] = $item->lapangan['nama_lapangan'];
            $row['harga_sewa']  = 'Rp. ' . format_uang($item->harga_sewa);
            $row['durasi']  = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_penyewaan_detail . '" value="' . $item->durasi . '">';
            $row['subtotal']    = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi_penyewaan.destroy', $item->id_penyewaan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;
            // deleteData('{{ route('transaksi_penyewaan.destroy', $item->id_penyewaan_detail) }}')
            // deleteData(`' . route('transaksi_penyewaan.destroy', $item->id_penyewaan_detail) . '`)
            // Hitung total harga dan total durasi
            $total += $item->harga_sewa * $item->durasi;
            $total_durasi += $item->durasi;
        }
    
        // Tambahkan kolom tersembunyi untuk total dan total durasi
        $data[] = [
            'nama_lapangan' => '<div class="total hide">' . $total . '</div><div class="total_durasi hide">' . $total_durasi . '</div>',
            'harga_sewa'    => '',
            'durasi'        => '',
            'subtotal'      => '',
            'aksi'          => '',
        ];
    
        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'durasi', 'nama_lapangan']) // tambahkan 'nama_lapangan' ke rawColumns
            ->make(true);
    }
    

    public function store(Request $request)
    {
        $lapangan = Lapangan::where('id_lapangan', $request->id_lapangan)->first();
        if (! $lapangan) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PenyewaanDetail();
        $detail->id_penyewaan = $request->id_penyewaan;
        $detail->id_lapangan = $lapangan->id_lapangan;
        $detail->harga_sewa = $lapangan->harga_sewa;
        $detail->durasi = 1; // Default 1 jam
        $detail->subtotal = $lapangan->harga_sewa;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenyewaanDetail::find($id);
        $detail->durasi = $request->durasi;
        $detail->subtotal = $detail->harga_sewa * $request->durasi;
        $detail->update();
    }

    public function destroy($id)
    {
        $penyewaanDetail = PenyewaanDetail::findOrFail($id); // Ambil data dengan ID
        $penyewaanDetail->delete(); // Hapus data
    
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
    

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
