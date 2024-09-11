<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriLapangan;

class KategoriLapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kategori_lapangan.index');
    }

    /**
     * Method untuk mengambil data kategori lapangan dalam format JSON untuk DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $kategoriLapangan = KategoriLapangan::orderBy('id_kategori_lapangan', 'desc')->get();

        return datatables()
            ->of($kategoriLapangan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategoriLapangan) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('kategori_lapangan.update', $kategoriLapangan->id_kategori_lapangan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('kategori_lapangan.destroy', $kategoriLapangan->id_kategori_lapangan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Implementasikan logika jika diperlukan
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_lapangan' => 'required|string|max:255',
        ]);

        $kategoriLapangan = new KategoriLapangan();
        $kategoriLapangan->nama_kategori_lapangan = $request->nama_kategori_lapangan;
        $kategoriLapangan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $kategoriLapangan = KategoriLapangan::findOrFail($id);

        return response()->json($kategoriLapangan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Implementasikan logika jika diperlukan
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_lapangan' => 'required|string|max:255',
        ]);

        $kategoriLapangan = KategoriLapangan::findOrFail($id);
        $kategoriLapangan->nama_kategori_lapangan = $request->nama_kategori_lapangan;
        $kategoriLapangan->update();

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
        $kategoriLapangan = KategoriLapangan::findOrFail($id);
        $kategoriLapangan->delete();

        return response(null, 204);
    }
};