<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaAkunProfilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaMagangController;
use App\Http\Controllers\ProgramStudiController;
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
            return view('admin.dashboard');
        });

        Route::get('/admin/profile', function () {
            return view('admin.dashboard');
        })->name('admin.profile');

        Route::get('/admin/pengguna/admin', [AdminController::class, 'index']);
        Route::get('/admin/pengguna/create', [AdminController::class, 'create']);
        Route::post('/admin/pengguna/admin', [AdminController::class, 'store']);
        Route::get('/admin/pengguna/admin/{id}', [AdminController::class, 'show']);
        Route::get('/admin/pengguna/admin/{id}/edit', [AdminController::class, 'edit']);
        Route::put('/admin/pengguna/admin/{id}', [AdminController::class, 'update']);
        Route::delete('/admin/pengguna/admin/{id}', [AdminController::class, 'destroy']);
        Route::patch('/admin/pengguna/admin/{id}/toggle-status', [AdminController::class, 'toggleStatus']);
        
        Route::resource('/admin/program_studi', ProgramStudiController::class)->except(['show']);
    });

    Route::middleware(['authorize:dosen'])->group(function () {
        Route::get('/dosen', [DosenController::class, 'index']);
        Route::get('/dosen/mahasiswabimbingan', [DosenController::class, 'tampilMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan');

        Route::get('/dosen/profile', [DosenController::class, 'profile'])->name('dosen.profile');
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
});
