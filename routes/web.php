<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\HaloController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index']);
Route::get('/halo', [HaloController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);

Route::get('/catatan', [CatatanController::class, 'index']);
Route::post('/catatan', [CatatanController::class, 'store']);
Route::get('/catatan/tambah', [CatatanController::class, 'create']);
Route::get('/catatan/{id}', [CatatanController::class, 'show']);
Route::get('/catatan/{id}/edit', [CatatanController::class, 'edit']);
Route::put('/catatan/{id}', [CatatanController::class, 'update']);
Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
