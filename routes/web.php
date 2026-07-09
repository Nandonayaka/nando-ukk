<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GachaController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\KoleksiPribadiController;

Route::get('/', function () {
    if (auth()->check()) {
        $isAdmin = (auth()->user()->role === 'administrator');
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
    Route::resource('users', UserController::class);
    
    // Category Management
    Route::resource('categories', \App\Http\Controllers\KategoriBukuController::class);
    
    // Pelanggan Route
    Route::get('/katalog', [BookController::class, 'katalog'])->name('katalog.index');
    Route::get('/history', [BookController::class, 'history'])->name('history.index');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/choose-pfp', [AuthController::class, 'showChoosePfp'])->name('pfp.choose');
    Route::post('/choose-pfp', [AuthController::class, 'updatePfp'])->name('pfp.update');

    // Gacha System
    Route::get('/gacha', [GachaController::class, 'index'])->name('gacha.index');
    Route::get('/gacha/koleksi', [GachaController::class, 'koleksi'])->name('gacha.koleksi');
    Route::post('/gacha/koleksi/{id}/claim', [GachaController::class, 'claim'])->name('gacha.claim');
    Route::post('/gacha/spin', [GachaController::class, 'spin'])->name('gacha.spin');

    // Inbox / Notifikasi
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::post('/inbox/{id}/read', [InboxController::class, 'markAsRead'])->name('inbox.read');
    Route::post('/inbox/read-all', [InboxController::class, 'markAllRead'])->name('inbox.readAll');
    Route::delete('/inbox/{id}', [InboxController::class, 'destroy'])->name('inbox.destroy');
    Route::delete('/inbox', [InboxController::class, 'clearAll'])->name('inbox.clearAll');
    Route::get('/inbox/unread-count', [InboxController::class, 'unreadCount'])->name('inbox.unreadCount');

    // Both Admin and Pelanggan can view these
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    
    // Create must come before {book} wildcard
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // Peminjaman logic
    Route::post('/books/{book}/pinjam', [BookController::class, 'pinjam'])->name('books.pinjam');
    Route::post('/books/{book}/ulasan', [BookController::class, 'storeUlasan'])->name('books.ulasan');
    Route::delete('/ulasan/{ulasan}', [BookController::class, 'deleteUlasan'])->name('ulasan.destroy');
    Route::post('/history/{peminjaman}/kembali', [BookController::class, 'kembalikan'])->name('books.kembalikan');
    Route::post('/history/{peminjaman}/bayar', [BookController::class, 'bayarDenda'])->name('books.bayar-denda');
    Route::post('/history/{peminjaman}/konfirmasi', [BookController::class, 'konfirmasi'])->name('books.konfirmasi');
    Route::post('/history/{peminjaman}/batalkan', [BookController::class, 'batalkan'])->name('books.batalkan');
    Route::post('/history/{peminjaman}/tolak', [BookController::class, 'tolak'])->name('books.tolak');
    Route::get('/history/export/pdf', [BookController::class, 'exportAllPdf'])->name('history.exportPdf');
    Route::get('/history/{peminjaman}/pdf', [BookController::class, 'exportPdf'])->name('history.pdf');

    // Koleksi (Favorit)
    Route::get('/favorit', [KoleksiPribadiController::class, 'index'])->name('koleksipribadi.index');
    Route::post('/favorit/{book}/toggle', [KoleksiPribadiController::class, 'toggle'])->name('koleksipribadi.toggle');
    
    // Admin & Petugas can do CRUD (Store, Edit, Update, Delete)
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});
