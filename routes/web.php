<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PeminjamanController::class, 'dashboard'])->name('dashboard');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/pinjam', [PeminjamanController::class, 'store'])->name('pinjam.store');
    Route::post('/acc/{id}', [PeminjamanController::class, 'acc'])->name('pinjam.acc');
    Route::post('/reject/{id}', [PeminjamanController::class, 'reject'])->name('pinjam.reject');
});

require __DIR__.'/auth.php';
