<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Member;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\Lapangan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\PenjualanDetail;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $lapangan = Lapangan::orderBy('nama_lapangan')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('produk', 'lapangan', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with(['produk', 'lapangan'])
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span>';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['nama_lapangan'] = $item->lapangan['nama_lapangan'] ?? '-';
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['harga_sewa']  = $item->harga_sewa ? 'Rp. '. format_uang($item->harga_sewa) : '-';
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['durasi']      = $item->durasi ? '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->durasi .'">' : '-';
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total_harga_jual = $item->harga_jual * $item->jumlah;
            $diskon = ($item->diskon / 100) * $total_harga_jual;
            $total_harga_jual_akhir = $total_harga_jual - $diskon;

            $total_harga_sewa = $item->harga_sewa * $item->durasi;

            $total += $total_harga_jual_akhir + $total_harga_sewa;
            $total_item += $item->jumlah;
        }

        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'nama_lapangan' => '',
            'harga_jual'  => '',
            'harga_sewa'  => '',
            'jumlah'      => '',
            'durasi'      => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah', 'durasi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // Ambil data produk dan lapangan berdasarkan input ID, atau atur ke null jika tidak ada
        $produk = Produk::find($request->id_produk);
        $lapangan = Lapangan::find($request->id_lapangan);
    
        // Inisialisasi variabel dengan default nilai
        $harga_jual = 0;
        $harga_sewa = 0;
        $diskon = 0;
    
        // Jika produk ada, ambil harga jual dan diskon dari produk
        if ($produk) {
            $harga_jual = $produk->harga_jual ?? 0;
            $diskon = $produk->diskon ?? 0;
        }
    
        // Jika lapangan ada, ambil harga sewa dari lapangan
        if ($lapangan) {
            $harga_sewa = $lapangan->harga_sewa ?? 0;
        }
    
        // Buat objek PenjualanDetail baru dan set properti
        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_lapangan = $lapangan->id_lapangan ?? null;
        $detail->id_produk = $produk->id_produk ?? null;
        $detail->harga_jual = $harga_jual;
        $detail->harga_sewa = $harga_sewa;
        $detail->jumlah = $request->jumlah ?? 1;
        $detail->durasi = $request->durasi ?? 1;
        $detail->diskon = $diskon;
    
        // Hitung subtotal
        $subtotalJual = $harga_jual - ($diskon / 100 * $harga_jual);
        $subtotalSewa = $harga_sewa * $detail->durasi;
        $detail->subtotal = $subtotalJual + $subtotalSewa;
    
        // Simpan detail penjualan
        $detail->save();
    
        return response()->json('Data berhasil disimpan', 200);
    }
    

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);

        if (!$detail) {
            return response()->json('Detail penjualan tidak ditemukan', 404);
        }

        $detail->jumlah = $request->jumlah;
        $detail->durasi = $request->durasi;

        $detail->subtotal = ($detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual)) 
                            + ($detail->harga_sewa * $request->durasi);

        $detail->save();

        return response()->json('Data berhasil diperbarui', 200);
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        if ($detail) {
            $detail->delete();
            return response(null, 204);
        }
        return response()->json('Detail penjualan tidak ditemukan', 404);
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
