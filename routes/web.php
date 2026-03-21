<?php

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
    Route::get('users/update', [UserController::class, 'index'])->name('users.update');

    // Placeholder route untuk edit dan password (sesuaikan nanti)
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('users/{user}/password', [UserController::class, 'password'])->name('users.password');
});

require __DIR__.'/auth.php';
