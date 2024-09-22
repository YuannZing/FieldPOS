<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Member;
use App\Models\Setting;
use App\Models\Lapangan;
use App\Models\Penyewaan;
use App\Models\PenyewaanDetail;
use Illuminate\Http\Request;
use PDF;

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
        return redirect()->route('transaksi-penyewaan.index');
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
            $lapangan = Lapangan::find($item->id_lapangan);
            $lapangan->status = 'available'; // Misalnya update status lapangan setelah selesai disewa
            $lapangan->update();
        }

        return redirect()->route('transaksi-penyewaan.selesai');
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

    public function destroy($id)
    {
        $penyewaan = Penyewaan::find($id);
        $detail = PenyewaanDetail::where('id_penyewaan', $penyewaan->id_penyewaan)->get();
        foreach ($detail as $item) {
            $item->delete();
        }
        $penyewaan->delete();

        return response(null, 204);
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
=======
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
>>>>>>> b3b79ae42465dcf566e62d81f4ec12d79b17bb41
}
