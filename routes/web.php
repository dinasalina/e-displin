<?php

use App\Http\Controllers\Master\KategoriDisiplinController;
use App\Http\Controllers\Master\KelasController;
use App\Http\Controllers\Master\KelasGuruController;
use App\Http\Controllers\Master\MuridController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\PenjagaController;
use App\Http\Controllers\Master\SekolahController;
use App\Http\Controllers\Master\TahunAkademikController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul 1: Master Data Management
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('sekolah', SekolahController::class)->except(['create', 'edit', 'show']);
        Route::resource('tahun-akademik', TahunAkademikController::class)->except(['create', 'edit', 'show']);
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('kelas', KelasController::class)->except(['create', 'edit', 'show']);
        Route::post('kelas-guru', [KelasGuruController::class, 'store'])->name('kelas-guru.store');
        Route::resource('murid', MuridController::class);
        Route::resource('penjaga', PenjagaController::class)->except(['create', 'edit', 'show']);
        Route::resource('kategori-disiplin', KategoriDisiplinController::class)->except(['create', 'edit', 'show']);
    });
});

require __DIR__.'/auth.php';
