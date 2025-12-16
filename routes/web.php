<?php

use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DasboardAdminController;
use App\Http\Controllers\GambaranUmumController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\KthController;
use App\Http\Controllers\KupsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonevController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\Passwordcontroller;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PotensiController;
use App\Http\Controllers\ProgreskerjaController;
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\RencanakerjaController;
use App\Http\Controllers\SubProgramController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PotensiKupsController;
use App\Http\Controllers\ProdukKupsController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RencanaAksi_6TahunController;
use App\Http\Controllers\SubpotensiKehutananController;
use App\Models\Pesan;
use App\Models\RencanaKerja;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

//client
Route::get('/', [ClientController::class, 'index'])->name('dasbor');
Route::get('/strategi', [ClientController::class, 'strategi'])->name('strategi');
Route::get('/rencana-aksi', [ClientController::class, 'rencanaAksi'])->name('rencanaAksi');
Route::get('/rencana-kerja', [ClientController::class, 'rencanaKerja'])->name('rencanaKerja');
Route::get('/monitoring-evaluasi', [ClientController::class, 'Monev'])->name('Monev');
Route::get('/progres-kerja', [ClientController::class, 'progres'])->name('progreskerja');
Route::get('/regu', [ClientController::class, 'regulasi'])->name('regu');
Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
// routes/web.php


//admin
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

Route::middleware(['authadmin', 'noCache'])->group(function () {

    Route::get('/admin', [DasboardAdminController::class, 'index'])->name('dashboard');

    Route::get('/banner', [BannerController::class, 'index'])->name('banner');
    Route::post('/banner-save', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banner-edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::put('/banner-update/{id}', [BannerController::class, 'update'])->name('banner.update');
    Route::delete('/banner-delete/{id}', [BannerController::class, 'destroy'])->name('banner.delete');

    Route::get('/strategi-admin', [SubProgramController::class, 'index'])->name('subprogram');
    Route::post('/strategi-create', [SubProgramController::class, 'store'])->name('subrogram.store');
    Route::put('/strategi-update/{id}', [SubProgramController::class, 'update'])->name('subprogram.update');
    Route::delete('/strategi-delete/{id}', [SubProgramController::class, 'destroy'])->name('subrogram.delete');



    Route::get('/rencan-aksi', [RencanaAksi_6TahunController::class, 'index'])->name('rencana6tahun');
    Route::get('/rencana-aksi/export-excel', [RencanaAksi_6TahunController::class, 'exportExcelAksi'])
        ->name('rencanaAksi.export.excel');
    Route::get('/rencan-aksi-create', [RencanaAksi_6TahunController::class, 'create'])->name('rencanaAksi.create');
    Route::post('/rencan-aksi-save', [RencanaAksi_6TahunController::class, 'store'])->name('rencanaAksi.store');
    Route::get('/rencan-aksi-edit/{id}', [RencanaAksi_6TahunController::class, 'edit'])->name('rencanaAksi.edit');
    Route::put('/rencan-aksi-update/{id}', [RencanaAksi_6TahunController::class, 'update'])->name('rencanaAksi.update');
    Route::delete('/rencan-aksi-delete/{id}', [RencanaAksi_6TahunController::class, 'destroy'])->name('rencanaAksi.destroy');

    Route::get('/rencan-kerja', [RencanakerjaController::class, 'index'])->name('rencanakerja');
    Route::get('/rencana/export-excel', [RencanakerjaController::class, 'exportExcel'])
        ->name('rencana.export.excel');
    Route::get('/get-rencana-aksi/{id_subprogram}', [RencanakerjaController::class, 'getRencanaAksi'])
        ->name('get.rencana.aksi');
    Route::get('/get-detail-rencana-aksi/{id}', [RencanakerjaController::class, 'getDetail']);
    Route::get('/rencana-create', [RencanakerjaController::class, 'create'])->name('rencana.create');
    Route::post('/rencana-store', [RencanakerjaController::class, 'store'])->name('rencana.store');
    Route::put('/rencana/{id}/validasi', [RencanakerjaController::class, 'updateStatus'])->name('rencana.validasi');
    Route::get('/rencana-kerja/{id}', [RencanakerjaController::class, 'show'])->name('rencana.show');
    Route::get('/rencana-edit/{id}', [RencanakerjaController::class, 'edit'])->name('rencana.edit');
    Route::put('/rencana-update/{id}', [RencanakerjaController::class, 'update'])->name('rencana.update');
    Route::delete('/rencana-delete/{id}', [RencanakerjaController::class, 'destroy'])->name('rencana.delete');
    Route::put('/renja/bulk-toggle-lock', [RencanakerjaController::class, 'bulkToggleLock'])->name('renja.bulk-lock');


    Route::get('/progres', [ProgreskerjaController::class, 'index'])->name('progres');
    Route::get('/progres-create', [ProgreskerjaController::class, 'create'])->name('progrescreate');
    Route::post('/progres-sive', [ProgreskerjaController::class, 'store'])->name('progres.store');
    Route::put('/progres/{id}/status', [ProgreskerjaController::class, 'updateStatus'])->name('progres.updateStatus');
    Route::get('/progres/{id}', [ProgresKerjaController::class, 'show'])->name('progres.show');
    Route::get('/progres-edit/{id}', [ProgreskerjaController::class, 'edit'])->name('progres.edit');
    Route::put('/progres-update/{id}', [ProgreskerjaController::class, 'update'])->name('progres.update');
    Route::delete('/progres-delete/{id}', [ProgreskerjaController::class, 'destroy'])->name('progres.delete');


    Route::get('/monev', [MonevController::class, 'index'])->name('monev');
    Route::get('/monev-create', [MonevController::class, 'create'])->name('monev.create');
    Route::get('/get-rencana-kerja/{id_subprogram}', [MonevController::class, 'getRencanaKerja']);
    Route::get('/get-detail-rencana-kerja/{id}', [MonevController::class, 'getDetailRencanaKerja']);
    Route::post('/monev-sive', [MonevController::class, 'store'])->name('monev.store');
    Route::post('/monev-foto-sive', [MonevController::class, 'storeFoto'])->name('foto-progres.store');
    Route::put('/monev/{id}/pesan', [MonevController::class, 'updatePesan'])->name('monev.pesan');
    Route::get('/monev/export', [MonevController::class, 'exportPDF'])->name('monev.export');
    Route::get('/monev/export-excel', [MonevController::class, 'exportExcel'])->name('monev.export.excel');
    Route::put('/monev/{id}/validasi', [MonevController::class, 'updateStatus'])->name('monev.validasi');
    Route::get('/monev-edit/{id}', [MonevController::class, 'edit'])->name('monev.edit');
    Route::put('/monev-update/{id}', [MonevController::class, 'update'])->name('monev.update');
    Route::delete('/monev-delete/{id}', [MonevController::class, 'destroy'])->name('monev.delete');
    Route::put('/monev/bulk-toggle-lock', [MonevController::class, 'bulkToggleLock'])->name('monev.bulk-lock');

    Route::get('/regulasi', [RegulasiController::class, 'index'])->name('regulasi');
    Route::get('/regulasi-create', [RegulasiController::class, 'create'])->name('regulasi.create');
    Route::post('/regulasi-store', [RegulasiController::class, 'store'])->name('regulasi.store');
    Route::get('/regulasi-edit/{id}', [RegulasiController::class, 'edit'])->name('regulasi.edit');
    Route::put('/regulasi-update/{id}', [RegulasiController::class, 'update'])->name('regulasi.update');
    Route::delete('regulasi-delete/{id}', [RegulasiController::class, 'destroy'])->name('regulasi.delete');


    Route::get('/opd', [OpdController::class, 'index'])->name('opd');
    Route::post('/opd-store', [OpdController::class, 'store'])->name('opd.store');
    Route::put('/opd-update/{id}', [OpdController::class, 'update'])->name('opd.update');
    Route::delete('/opd-delete/{id}', [OpdController::class, 'destroy'])->name('opd.destroy');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::get('/pengguna-create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna-store', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna-edit/{id}', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna-update/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna-delete/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil-update', [ProfilController::class, 'updateProfile'])->name('profil.update');

    Route::post('/ganti-password', [LoginController::class, 'update_password'])->name('update.password');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
