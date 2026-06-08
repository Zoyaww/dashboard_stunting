<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

// Login routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/', [DashboardController::class, 'index']);

// Admin routes
Route::get('/admin', [AdminController::class, 'dashboard']);
Route::get('/admin/locations', [AdminController::class, 'index']);
Route::get('/admin/locations/create', [AdminController::class, 'create']);
Route::post('/admin/locations', [AdminController::class, 'store']);
Route::get('/admin/locations/{location}/edit', [AdminController::class, 'edit']);
Route::put('/admin/locations/{location}', [AdminController::class, 'update']);
Route::delete('/admin/locations/{location}', [AdminController::class, 'destroy']);