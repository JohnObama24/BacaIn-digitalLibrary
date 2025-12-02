<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\book\BookController;
use App\Http\Controllers\book\KategoriController;
use App\Http\Controllers\book\PeminjamanController;
use App\Http\Controllers\MemberProfileController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get("/login", [AuthController::class, "ShowLogin"])->name("login");
Route::post("/login", [AuthController::class, "login"])->name("login.process");
Route::get("/register", [AuthController::class, "showRegister"])->name("register");
Route::post("/register", [AuthController::class, "Register"])->name("register.process");
Route::post("/logout", [AuthController::class, "Logout"])->name("logout");

Route::middleware(['role:user'])->group(function () {
    Route::get('/member/home', [BookController::class, 'getBook'])->name('member.home');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('book.detail');

    Route::post('/pinjam', [PeminjamanController::class, 'pinjam'])->name('pinjam');
    Route::get('/peminjaman-saya', [PeminjamanController::class, 'userPeminjaman'])->name('user.peminjaman');
    Route::post('/peminjaman/{id}/return-ebook', [PeminjamanController::class, 'returnEbook'])->name('peminjaman.return-ebook');
    Route::get('/ebook/read/{id}', [PeminjamanController::class, 'readEbook'])->name('ebook.read');

    Route::get('/profile', [MemberProfileController::class, 'index'])->name('member.profile');
    Route::get('/profile/favorites', [MemberProfileController::class, 'favorites'])->name('member.favorites');
    Route::post('/profile/favorites/toggle', [MemberProfileController::class, 'toggleFavorite'])->name('member.favorites.toggle');
    Route::get('/profile/history', [MemberProfileController::class, 'history'])->name('member.history');
});

Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', fn() => view('admin.index'))->name('admin.dashboard');

    Route::resource('buku', BookController::class)->except(['show']);

    Route::resource('kategori', KategoriController::class)->except(['show']);

    Route::get('/user', [UserManagementController::class, 'index'])->name('user.index');
    Route::get('/userCreate', [UserManagementController::class, 'create'])->name('user.create');
    Route::post('/userCreate', [UserManagementController::class, 'store'])->name('user.store');
    Route::get('/user/edit{id}', [UserManagementController::class, 'edit'])->name('user.edit');
    Route::Put('/user/edit{id}', [UserManagementController::class, 'update'])->name('user.update');
    Route::delete('/user/delete{id}', [UserManagementController::class, 'destroy'])->name('user.destroy');

    Route::get('laporan', function () {
        return view('admin.laporan.index');
    })->name('laporan.index');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('/peminjaman/{id}/konfirmasi-denda', [PeminjamanController::class, 'konfirmasiDenda'])->name('peminjaman.konfirmasi-denda');
});


