<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use App\Models\KategoriLapangan;

class LapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori_lapangan = KategoriLapangan::all()->pluck('nama_kategori_lapangan', 'id_kategori_lapangan');
        return view('lapangan.index', compact('kategori_lapangan'));
    }

    public function data()
    {
        // $lapangan = Lapangan::leftJoin('kategori_lapangan', 'kategori_lapangan.id_kategori_lapangan', 'lapangan.id_kategori_lapangan')
        //     ->select('lapangan.*', 'kategori_lapangan.nama_kategori')
        //     ->get();

        $lapangan = Lapangan::select('lapangan.*', 'kategori_lapangan.nama_kategori_lapangan')
            ->leftJoin('kategori_lapangan', 'kategori_lapangan.id_kategori_lapangan', '=', 'lapangan.id_kategori_lapangan')
            ->get();

        return datatables()
            ->of($lapangan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($lapangan) {
                return '
                    <input type="checkbox" name="id_lapangan[]" value="'. $lapangan->id_lapangan .'">
                ';
            })
            ->addColumn('harga_sewa', function ($lapangan) {
                return format_uang($lapangan->harga_sewa);
            })
            ->addColumn('aksi', function ($lapangan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('lapangan.update', $lapangan->id_lapangan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('lapangan.destroy', $lapangan->id_lapangan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lapangan = Lapangan::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lapangan = Lapangan::find($id);

        return response()->json($lapangan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::find($id);
        $lapangan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lapangan = Lapangan::find($id);
        $lapangan->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_lapangan as $id) {
            $lapangan = Lapangan::find($id);
            $lapangan->delete();
        }

        return response(null, 204);
    }
}
