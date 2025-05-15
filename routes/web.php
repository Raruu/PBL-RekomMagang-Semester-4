<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaAkunProfilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaMagangController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\PerusahaanMitraController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);

Route::get('demo', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/' . Auth::user()->getRole());
    });

    Route::middleware(['authorize:admin'])->group(function () {
        // Dashboard admin
        Route::get('/admin', function () {
            return view('admin.profil_admin.dashboard');
        });

        Route::get('/admin/profile', function () {
            return view('admin.profil_admin.dashboard');
        })->name('admin.profile');

        Route::get('/admin/pengguna/admin', [AdminController::class, 'index']);
        Route::get('/admin/pengguna/create', [AdminController::class, 'create']);
        Route::post('/admin/pengguna/admin', [AdminController::class, 'store']);
        Route::get('/admin/pengguna/admin/{id}', [AdminController::class, 'show']);
        Route::get('/admin/pengguna/admin/{id}/edit', [AdminController::class, 'edit']);
        Route::put('/admin/pengguna/admin/{id}', [AdminController::class, 'update']);
        Route::delete('/admin/pengguna/admin/{id}', [AdminController::class, 'destroy']);
        Route::patch('/admin/pengguna/admin/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggle-status');

        Route::resource('/admin/program_studi', ProgramStudiController::class)->except(['show']);
    });

    Route::middleware(['authorize:dosen'])->group(function () {
        Route::get('/dosen', [DosenController::class, 'index']);
        Route::get('/dosen/mahasiswabimbingan', [DosenController::class, 'tampilMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan');
        Route::get('/dosen/mahasiswabimbingan/{id}/logAktivitas', [DosenController::class, 'logAktivitas'])->name('dosen.detail.logAktivitas');
        Route::get('/dosen/mahasiswabimbingan/{id}/detail', [DosenController::class, 'detailMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan.detail');
        Route::get('/dosen/profile', [DosenController::class, 'profile'])->name('dosen.profile');
        Route::get('/dosen/profile/edit', [DosenController::class, 'editProfile'])->name('dosen.edit-profil');
        Route::post('/dosen/profile/update', [DosenController::class, 'updateProfile'])->name('dosen.update-profil');
    });

    Route::middleware(['authorize:mahasiswa'])->group(function () {
        Route::get('/mahasiswa', [MahasiswaAkunProfilController::class, 'index']);
        Route::get('/mahasiswa/profile', [MahasiswaAkunProfilController::class, 'profile'])->name('mahasiswa.profile');
        Route::get('/mahasiswa/profile/edit', [MahasiswaAkunProfilController::class, 'profile']);
        Route::post('/mahasiswa/profile/update', [MahasiswaAkunProfilController::class, 'update']);
        Route::post('/mahasiswa/profile/update-password', [MahasiswaAkunProfilController::class, 'changePassword']);
        Route::get('/mahasiswa/dokumen', [MahasiswaAkunProfilController::class, 'dokumen']);
        Route::post('/mahasiswa/dokumen/upload', [MahasiswaAkunProfilController::class, 'dokumenUpload']);
        Route::get('/mahasiswa/magang', [MahasiswaMagangController::class, 'magang']);
        Route::get('/mahasiswa/magang/{lowongan_id}', [MahasiswaMagangController::class, 'detail']);
    });

    Route::prefix('admin/perusahaan')->group(function () {
        Route::get('/', [PerusahaanMitraController::class, 'index'])->name('perusahaan.index');
        Route::post('/list', [PerusahaanMitraController::class, 'list'])->name('perusahaan.list'); // untuk DataTables AJAX
        Route::get('/create', [PerusahaanMitraController::class, 'create'])->name('perusahaan.create');
        Route::post('/store', [PerusahaanMitraController::class, 'store'])->name('perusahaan.store');
        Route::get('/{id}/edit', [PerusahaanMitraController::class, 'edit'])->name('perusahaan.edit');
        Route::put('/{id}/update', [PerusahaanMitraController::class, 'update'])->name('perusahaan.update');
        Route::delete('/{id}/delete', [PerusahaanMitraController::class, 'destroy'])->name('perusahaan.destroy');
    });
});
