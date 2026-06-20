<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StuntingController;
use App\Http\Controllers\RekapKecamatanController;
use App\Http\Controllers\RekapPuskesmasController;
use App\Http\Controllers\ImportStuntingController;

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Root — arahkan sesuai role, jangan hardcode ke admin
Route::get('/', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard'); // ganti sesuai view dashboard user biasa
})->middleware('auth')->name('home');

Route::get('/api/stuntings', [StuntingController::class, 'api']);

// Admin routes — SEMUA dipindah ke dalam group yang terlindungi
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/locations', [AdminController::class, 'index'])->name('locations.index');
        Route::get('/locations/create', [AdminController::class, 'create'])->name('locations.create');
        Route::post('/locations', [AdminController::class, 'store'])->name('locations.store');
        Route::get('/locations/{location}/edit', [AdminController::class, 'edit'])->name('locations.edit');
        Route::put('/locations/{location}', [AdminController::class, 'update'])->name('locations.update');
        Route::delete('/locations/{location}', [AdminController::class, 'destroy'])->name('locations.destroy');

        Route::resource('rekap-kecamatan', RekapKecamatanController::class);
        Route::resource('rekap-puskesmas', RekapPuskesmasController::class);

       Route::get('/stuntings', [AdminController::class, 'index'])->name('stuntings.index');
Route::get('/stuntings/create', [AdminController::class, 'create'])->name('stuntings.create');
Route::post('/stuntings', [AdminController::class, 'store'])->name('stuntings.store');
Route::get('/stuntings/{stunting}/edit', [AdminController::class, 'edit'])->name('stuntings.edit');
Route::put('/stuntings/{stunting}', [AdminController::class, 'update'])->name('stuntings.update');
Route::delete('/stuntings/{stunting}', [AdminController::class, 'destroy'])->name('stuntings.destroy');
    });