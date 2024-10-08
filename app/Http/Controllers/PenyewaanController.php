<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Member;
use App\Models\Setting;
use App\Models\Lapangan;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use App\Models\PenyewaanDetail;
use Illuminate\Support\Facades\Log;


class PenyewaanController extends Controller
{
    public function index()
    {
        return view('penyewaan.index');
    }

    public function data()
    {
        $penyewaan = Penyewaan::with('member')->orderBy('id_penyewaan', 'desc')->get();

        return datatables()
            ->of($penyewaan)
            ->addIndexColumn()
            ->addColumn('total_durasi', function ($penyewaan) {
                return format_uang($penyewaan->total_durasi);
            })
            ->addColumn('total_harga', function ($penyewaan) {
                return 'Rp. ' . format_uang($penyewaan->total_harga);
            })
            ->addColumn('bayar', function ($penyewaan) {
                return 'Rp. ' . format_uang($penyewaan->bayar);
            })
            ->addColumn('tanggal', function ($penyewaan) {
                return tanggal_indonesia($penyewaan->created_at, false);
            })
            ->addColumn('kode_member', function ($penyewaan) {
                $member = $penyewaan->member->kode_member ?? '';
                return '<span class="label label-success">' . $member . '</span>';
            })
            ->editColumn('diskon', function ($penyewaan) {
                return $penyewaan->diskon . '%';
            })
            ->editColumn('kasir', function ($penyewaan) {
                return $penyewaan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penyewaan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('penyewaan.show', $penyewaan->id_penyewaan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('penyewaan.destroy', $penyewaan->id_penyewaan) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    public function create()
    {
        $penyewaan = new Penyewaan();
        $penyewaan->id_member = null;
        $penyewaan->total_durasi = 0;
        $penyewaan->total_harga = 0;
        $penyewaan->bayar = 0;
        $penyewaan->diterima = 0;
        $penyewaan->id_user = auth()->id();
        $penyewaan->save();

        session(['id_penyewaan' => $penyewaan->id_penyewaan]);
        return redirect()->route('transaksi_penyewaan.index');
    }

    public function store(Request $request)
    {
        $penyewaan = Penyewaan::findOrFail($request->id_penyewaan);
        $penyewaan->id_member = $request->id_member;
        $penyewaan->total_durasi = $request->total_durasi;
        $penyewaan->total_harga = $request->total;
        $penyewaan->bayar = $request->bayar;
        $penyewaan->diterima = $request->diterima;
        $penyewaan->update();

        $detail = PenyewaanDetail::where('id_penyewaan', $penyewaan->id_penyewaan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();
            $lapangan = Lapangan::find($item->id_lapangan);
            // $lapangan->status = 'available'; // Misalnya update status lapangan setelah selesai disewa
            $lapangan->update();
        }

        return redirect()->route('transaksi_penyewaan.selesai');
    }

    public function show($id)
    {
        $detail = PenyewaanDetail::with('lapangan')->where('id_penyewaan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('nama_lapangan', function ($detail) {
                return $detail->lapangan->nama_lapangan;
            })
            ->addColumn('harga_sewa', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_sewa);
            })
            ->addColumn('durasi', function ($detail) {
                return format_uang($detail->durasi) . ' jam';
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['nama_lapangan'])
            ->make(true);
    }

    // public function destroy($id)
    // {
    //     $penyewaan = Penyewaan::find($id);
    //     $detail = PenyewaanDetail::where('id_penyewaan', $penyewaan->id_penyewaan)->get();
    //     foreach ($detail as $item) {
    //         $item->delete();
    //     }
    //     $penyewaan->delete();

    //     return response(null, 204);
    // }
    public function destroy($id)
{
    try {
        $penyewaan = Penyewaan::findOrFail($id);
        $detail = PenyewaanDetail::where('id_penyewaan', $penyewaan->id_penyewaan)->get();
        foreach ($detail as $item) {
            $item->delete();
        }
        $penyewaan->delete();
        return response()->json(['success' => 'Data berhasil dihapus'], 200);
    } catch (\Exception $e) {
        Log::error('Error saat menghapus data: ' . $e->getMessage());
        return response()->json(['error' => 'Terjadi kesalahan saat menghapus data'], 500);
    }
}


    public function selesai()
    {
        $setting = Setting::first();

        return view('penyewaan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penyewaan = Penyewaan::find(session('id_penyewaan'));
        if (! $penyewaan) {
            abort(404);
        }
        $detail = PenyewaanDetail::with('lapangan')
            ->where('id_penyewaan', session('id_penyewaan'))
            ->get();

        return view('penyewaan.nota_kecil', compact('setting', 'penyewaan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penyewaan = Penyewaan::find(session('id_penyewaan'));
        if (! $penyewaan) {
            abort(404);
        }
        $detail = PenyewaanDetail::with('lapangan')
            ->where('id_penyewaan', session('id_penyewaan'))
            ->get();

        $pdf = PDF::loadView('penyewaan.nota_besar', compact('setting', 'penyewaan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
