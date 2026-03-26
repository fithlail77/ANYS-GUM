<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/users', [UserController::class, 'index'])->middleware('auth')->name('users.index');

Route::middleware(['auth'])->group(function () {
    // Route untuk menampilkan halaman user
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');    
    Route::put('users/update', [UserController::class, 'update'])->name('users.update');

    // Placeholder route untuk edit dan password (sesuaikan nanti)
    Route::get('users/{user}/show', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/{user}/password', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::get('users/hapus/{id}', [UserController::class, 'destroy']);

    // Routing Resource standar untuk CRUD
    Route::get('assets/data', [AssetController::class, 'data'])->name('assets.data');
    Route::get('assets/print', [AssetController::class, 'printIndex'])->name('assets.print-index');
    Route::post('assets/print/process', [AssetController::class, 'printProcess'])->name('assets.print-process');
    Route::post('assets/{asset}/transfer', [AssetController::class, 'transfer'])->name('assets.transfer');
    Route::resource('assets', AssetController::class);
});

require __DIR__.'/auth.php';
