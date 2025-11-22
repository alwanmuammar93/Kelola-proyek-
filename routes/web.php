<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/home', function () {
    if (!session('is_login')) {
        return redirect()->route('login');
    }
    return app()->call('App\Http\Controllers\AdminController@index');
})->name('landing');

/*
|--------------------------------------------------------------------------
| DEBUG: GENERATE PASSWORD HASH
|--------------------------------------------------------------------------
*/
Route::get('/generate-hash', function () {
    $password = 'password_yang_seharusnya_valid';
    $hashedPassword = Hash::make($password);
    return "Password Asli: {$password} <br> HASH BARU: <b>{$hashedPassword}</b>";
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    if (!session('is_login')) {
        return redirect()->route('login');
    }
    return app()->call('App\Http\Controllers\AdminController@index');
})->name('admin.index');

/*
|--------------------------------------------------------------------------
| ADMIN – LINK KWITANSI DARI DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/admin/kwitansi', [KwitansiController::class, 'index'])
    ->name('admin.kwitansi.index');

/*
|--------------------------------------------------------------------------
| ADMIN – LINK KEL0LA RAB DARI DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/admin/rab', function () {
    return redirect()->route('rab.select_project');
})->name('admin.rab.index');

/*
|--------------------------------------------------------------------------
| PROYEK
|--------------------------------------------------------------------------
*/
Route::prefix('proyek')->group(function () {
    Route::get('/', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/create', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('/', [ProyekController::class, 'store'])->name('proyek.store');
    Route::get('/{id_proyek}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
    Route::put('/{id_proyek}', [ProyekController::class, 'update'])->name('proyek.update');
    Route::delete('/{id_proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');
});

/*
|--------------------------------------------------------------------------
| RAB
|--------------------------------------------------------------------------
*/
Route::prefix('rab')->group(function () {

    Route::get('/', [RabController::class, 'dashboard'])
        ->name('rab.dashboard');

    Route::get('/pilih-proyek', [RabController::class, 'selectProject'])
        ->name('rab.select_project');

    Route::get('/proyek/{id_proyek}', [RabController::class, 'index'])
        ->name('rab.index');

    Route::get('/create/{id_proyek}', [RabController::class, 'create'])
        ->name('rab.create');

    Route::post('/store/{id_proyek}', [RabController::class, 'store'])
        ->name('rab.store');

    Route::get('/edit/{id_rab}', [RabController::class, 'edit'])
        ->name('rab.edit');

    Route::put('/update/{id_rab}', [RabController::class, 'update'])
        ->name('rab.update');

    Route::delete('/destroy/{id_rab}', [RabController::class, 'destroy'])
        ->name('rab.destroy');

    Route::get('/detail/{id_rab}', [RabController::class, 'getRabDetails'])
        ->name('rab.detail');
});

/*
|--------------------------------------------------------------------------
| TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::prefix('transaksi')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

/*
|--------------------------------------------------------------------------
| KWITANSI
|--------------------------------------------------------------------------
*/
Route::prefix('kwitansi')->group(function () {

    Route::get('/search', [KwitansiController::class, 'search'])->name('kwitansi.search');

    Route::get('/getTotalSumber', [KwitansiController::class, 'getTotalSumber'])
        ->name('kwitansi.getTotalSumber');

    Route::get('/', [KwitansiController::class, 'index'])->name('kwitansi.index');
    Route::post('/', [KwitansiController::class, 'store'])->name('kwitansi.store');
    Route::get('/create', [KwitansiController::class, 'create'])->name('kwitansi.create');
    Route::get('/{id_kwitansi}/edit', [KwitansiController::class, 'edit'])->name('kwitansi.edit');
    Route::put('/{id_kwitansi}', [KwitansiController::class, 'update'])->name('kwitansi.update');
    Route::delete('/{id_kwitansi}', [KwitansiController::class, 'destroy'])->name('kwitansi.destroy');

    Route::put('/{id_kwitansi}/ubah-status', [KwitansiController::class, 'ubahStatus'])
        ->name('kwitansi.ubahStatus');
});

/*
|--------------------------------------------------------------------------
| PENJUALAN
|--------------------------------------------------------------------------
*/
Route::prefix('penjualan')->group(function () {
    Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/{id_barang}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/{id_barang}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/{id_barang}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
});

/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
Route::prefix('laporan')->group(function () {

    // HALAMAN CRUD
    Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/', [LaporanController::class, 'store'])->name('laporan.store');

    // ====================================================
    // 🔥 ROUTE UTAMA DROPDOWN (LIST + DETAIL)
    // ====================================================
    // /laporan/get-data?sumber=RAB
    // /laporan/get-data?sumber=RAB&id=3
    Route::get('/get-data', [LaporanController::class, 'getData'])
        ->name('laporan.getData');

    // ====================================================
    // OPSIONAL (TIDAK DIPAKAI DROPDOWN)
    // ====================================================
    Route::get('/get-data-list/{source}', [LaporanController::class, 'getDataList'])
        ->name('laporan.getDataList');

    Route::get('/get-detail/{source}/{id}', [LaporanController::class, 'getDetail'])
        ->name('laporan.getDetail');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD ALIAS
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (!session('is_login')) {
        return redirect()->route('login');
    }
    return app()->call('App\Http\Controllers\AdminController@index');
})->name('dashboard');

