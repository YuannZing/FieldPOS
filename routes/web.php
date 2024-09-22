<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    KategoriLapanganController, // Tambahkan ini
    PenyewaanController,
    PenyewaanDetailController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
    LapanganController,
    JadwalLapanganController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        // Tambahkan rute untuk Kategori Lapangan
        Route::get('/kategori_lapangan/data', [KategoriLapanganController::class, 'data'])->name('kategori_lapangan.data');
        Route::resource('/kategori_lapangan', KategoriLapanganController::class);

        Route::get('/lapangan/data', [LapanganController::class, 'data'])->name('lapangan.data');
        Route::post('/lapangan/delete-selected', [LapanganController::class, 'deleteSelected'])->name('lapangan.delete_selected');
        Route::resource('/lapangan', LapanganController::class);

        Route::get('/jadwal/data', [JadwalLapanganController::class, 'data'])->name('jadwal.data');
        Route::post('/jadwal/delete-selected', [JadwalLapanganController::class, 'deleteSelected'])->name('jadwal.delete_selected');
        Route::resource('/jadwal', JadwalLapanganController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

        // Rute untuk Penyewaan (mirip dengan Penjualan)
        Route::get('/penyewaan/data', [PenyewaanController::class, 'data'])->name('penyewaan.data');
        Route::get('/penyewaan', [PenyewaanController::class, 'index'])->name('penyewaan.index');
        Route::get('/penyewaan/{id}', [PenyewaanController::class, 'show'])->name('penyewaan.show');
        Route::delete('/penyewaan/{id}', [PenyewaanController::class, 'destroy'])->name('penyewaan.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        // Rute untuk transaksi penyewaan (mirip dengan transaksi penjualan)
        Route::get('/transaksi-penyewaan/baru', [PenyewaanController::class, 'create'])->name('transaksi-penyewaan.baru');
        Route::post('/transaksi-penyewaan/simpan', [PenyewaanController::class, 'store'])->name('transaksi-penyewaan.simpan');
        Route::get('/transaksi-penyewaan/selesai', [PenyewaanController::class, 'selesai'])->name('transaksi-penyewaan.selesai');
        Route::get('/transaksi-penyewaan/nota-kecil', [PenyewaanController::class, 'notaKecil'])->name('transaksi-penyewaan.nota_kecil');
        Route::get('/transaksi-penyewaan/nota-besar', [PenyewaanController::class, 'notaBesar'])->name('transaksi-penyewaan.nota_besar');

        Route::get('/transaksi-penyewaan/{id}/data', [PenyewaanDetailController::class, 'data'])->name('transaksi-penyewaan.data');
        Route::get('/transaksi-penyewaan/loadform/{diskon}/{total}/{diterima}', [PenyewaanDetailController::class, 'loadForm'])->name('transaksi-penyewaan.load_form');
        Route::resource('/transaksi-penyewaan', PenyewaanDetailController::class)
            ->except('create', 'show', 'edit');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});
