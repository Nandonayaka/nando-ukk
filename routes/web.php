<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (auth()->check()) {
        $isAdmin = (auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas');
        return $isAdmin ? redirect()->route('books.index') : redirect()->route('katalog.index');
    }
    return view('auth.login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Member Management (Administrator Only)
    Route::resource('users', UserController::class)->middleware('auth');
    
    // Pelanggan Route
    Route::get('/katalog', [BookController::class, 'katalog'])->name('katalog.index');
    Route::get('/history', [BookController::class, 'history'])->name('history.index');

    // Both Admin and Pelanggan can view these
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    
    // Create must come before {book} wildcard
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // Peminjaman logic
    Route::post('/books/{book}/pinjam', [BookController::class, 'pinjam'])->name('books.pinjam');
    Route::post('/history/{peminjaman}/kembali', [BookController::class, 'kembalikan'])->name('books.kembalikan');
    
    // Admin & Petugas can do CRUD (Store, Edit, Update, Delete)
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});
