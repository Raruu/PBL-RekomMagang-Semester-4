<?php

use App\Http\Controllers\AdminMagangController;
use App\Http\Controllers\AdminProfilAdminController;
use App\Http\Controllers\AdminProfilDosenController;
use App\Http\Controllers\AdminProfilMahasiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaAkunProfilController;
use App\Http\Controllers\MahasiswaMagangController;
use App\Http\Controllers\MahasiswaPengajuanController;
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
        
        // admin
        Route::get('/admin/pengguna/admin', [AdminProfilAdminController::class, 'index']);
        Route::get('/admin/pengguna/create', [AdminProfilAdminController::class, 'create']);
        Route::post('/admin/pengguna/admin', [AdminProfilAdminController::class, 'store']);
        Route::get('/admin/pengguna/admin/{id}', [AdminProfilAdminController::class, 'show']);
        Route::get('/admin/pengguna/admin/{id}/edit', [AdminProfilAdminController::class, 'edit']);
        Route::put('/admin/pengguna/admin/{id}', [AdminProfilAdminController::class, 'update']);
        Route::delete('/admin/pengguna/admin/{id}', [AdminProfilAdminController::class, 'destroy']);
        Route::patch('/admin/pengguna/admin/{id}/toggle-status', [AdminProfilAdminController::class, 'toggleStatus'])->name('admin.toggle-status');

        // Dosen
        Route::get('/admin/pengguna/dosen', [AdminProfilDosenController::class, 'index']);
        Route::patch('/admin/pengguna/dosen/{id}/toggle-status', [AdminProfilDosenController::class, 'toggleStatus'])->name('admin.toggle-status');

        // Mahasiswa
        Route::get('/admin/pengguna/mahasiswa', [AdminProfilMahasiswaController::class, 'index']);

        Route::resource('/admin/program_studi', ProgramStudiController::class)->except(['show']);

        Route::get('/admin/perusahaan/', [PerusahaanMitraController::class, 'index']);
        Route::get('/admin/perusahaan/create', [PerusahaanMitraController::class, 'create']);
        Route::post('/admin/perusahaan/', [PerusahaanMitraController::class, 'store']);
        Route::get('/admin/perusahaan/{id}', [PerusahaanMitraController::class, 'show']);
        Route::get('/admin/perusahaan/{id}/edit', [PerusahaanMitraController::class, 'edit']);
        Route::put('/admin/perusahaan/{id}', [PerusahaanMitraController::class, 'update']);
        Route::delete('/admin/perusahaan/{id}', [PerusahaanMitraController::class, 'destroy']);
        Route::patch('/admin/perusahaan/{id}/toggle-status', [PerusahaanMitraController::class, 'toggleStatus'])->name('admin.toggle-status');

        // MAGANG: Keagiatan
        Route::get('/admin/magang/kegiatan', [AdminMagangController::class, 'kegiatan'])->name('admin.magang.kegiatan');
    });

    Route::middleware(['authorize:dosen'])->group(function () {
        Route::get('/dosen', [DosenController::class, 'index']);
        Route::get('/dosen/mahasiswabimbingan', [DosenController::class, 'tampilMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan');
        Route::get('/dosen/mahasiswabimbingan/{id}/logAktivitas', [DosenController::class, 'logAktivitas'])->name('dosen.detail.logAktivitas');
        Route::get('/dosen/mahasiswabimbingan/{id}/detail', [DosenController::class, 'detailMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan.detail');
        Route::get('/dosen/profile', [DosenController::class, 'profile'])->name('dosen.profile');
    });

    Route::middleware(['authorize:mahasiswa'])->group(function () {
        Route::get('/mahasiswa', [MahasiswaAkunProfilController::class, 'index'])->name('mahasiswa.index');
        // PROFILE
        Route::get('/mahasiswa/profile', [MahasiswaAkunProfilController::class, 'profile'])->name('mahasiswa.profile');
        Route::get('/mahasiswa/profile/edit', [MahasiswaAkunProfilController::class, 'profile'])->name('mahasiswa.profile.edit');
        Route::post('/mahasiswa/profile/update', [MahasiswaAkunProfilController::class, 'update'])->name('mahasiswa.profile.update');
        Route::post('/mahasiswa/profile/update-password', [MahasiswaAkunProfilController::class, 'changePassword'])->name('mahasiswa.profile.update-password');
        Route::get('/mahasiswa/dokumen', [MahasiswaAkunProfilController::class, 'dokumen'])->name('mahasiswa.dokumen');
        Route::post('/mahasiswa/dokumen/upload', [MahasiswaAkunProfilController::class, 'dokumenUpload'])->name('mahasiswa.dokumen.upload');
        // MAGANG
        Route::get('/mahasiswa/magang', [MahasiswaMagangController::class, 'index'])->name('mahasiswa.magang');
        Route::get('/mahasiswa/magang/lowongan/', function () {
            return redirect('/mahasiswa/magang');
        });
        Route::get('/mahasiswa/magang/lowongan/{lowongan_id}', [MahasiswaMagangController::class, 'magangDetail'])->name('mahasiswa.magang.lowongan.detail');
        Route::get('/mahasiswa/magang/lowongan/{lowongan_id}/ajukan', [MahasiswaMagangController::class, 'ajukan'])->name('mahasiswa.magang.lowongan.ajukan');
        Route::post('/mahasiswa/magang/lowongan/{lowongan_id}/ajukan', [MahasiswaMagangController::class, 'ajukanPost'])->name('mahasiswa.magang.lowongan.ajukan.post');
        // PENGAJUAN
        Route::get('/mahasiswa/magang/pengajuan', [MahasiswaPengajuanController::class, 'index'])->name('mahasiswa.magang.pengajuan');
        Route::get('/mahasiswa/magang/pengajuan/{pengajuan_id}', [MahasiswaPengajuanController::class, 'pengajuanDetail'])->name('mahasiswa.magang.pengajuan.detail');
        Route::delete('/mahasiswa/magang/pengajuan/{pengajuan_id}', [MahasiswaPengajuanController::class, 'pengajuanDelete'])->name('mahasiswa.magang.pengajuan.delete');
    });
});
