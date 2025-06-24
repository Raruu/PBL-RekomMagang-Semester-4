<?php

use App\Http\Controllers\AdminBidangIndustriController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMagangController;
use App\Http\Controllers\AdminProfilAdminController;
use App\Http\Controllers\AdminProfilDosenController;
use App\Http\Controllers\AdminProfilMahasiswaController;
use App\Http\Controllers\AdminStatistikController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\AdminEvaluasiSPKController;
use App\Http\Controllers\AdminLowonganMagangController;
use App\Http\Controllers\AdminManajemenPeriodeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MahasiswaAkunProfilController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaMagangController;
use App\Http\Controllers\MahasiswaPengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\AdminPerusahaanMitraController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminTagKeahlianController;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware('guest')->group(function () {
    Route::get('/runmigrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migrasi sukses';
    });

    Route::get('/runseed', function () {
        Artisan::call('db:seed', ['--class' => 'DataAkademikSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'PenggunaSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'PerusahaanLowonganSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'PengalamanMahasiswaSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'PengajuanMagangSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'LokasiSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'SPKSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'FeedbackSpkSeeder', '--force' => true]);
        return 'Seeding sukses';
    });
    Route::get('/migrateseed', function () {
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        return 'Migration and seeding completed successfully';
    });


    Route::get('/landing', [LandingController::class, 'index'])->name('landing');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/' . Auth::user()->getRole());
    } else {
        return redirect('/landing');
    }
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifikasi', function () {
        return redirect('/' . Auth::user()->getRole() . '/notifikasi');
    })->name('notifikasi');
    Route::get('/notifikasi/getunread', [NotificationController::class, 'getUnreaded'])->name('notifikasi.getunread');
    Route::patch('/notifikasi/read/{id}', [NotificationController::class, 'read'])->name('notifikasi.read');
    Route::patch('/notifikasi/readall', [NotificationController::class, 'readall'])->name('notifikasi.readall');

    Route::middleware(['authorize:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/notifikasi', [NotificationController::class, 'index'])->name('admin.notifikasi');

        // Admin AJAX Routes untuk statistik
        Route::get('/admin/user-stats', [AdminController::class, 'getUserStats'])->name('admin.user-stats');
        Route::get('/admin/user-list', [AdminController::class, 'getUserList'])->name('admin.user-list');

        // Admin
        Route::prefix('admin/pengguna/admin')->group(function () {
            Route::get('/', [AdminProfilAdminController::class, 'index'])->name('admin.admin.index');
            Route::get('/', [AdminProfilAdminController::class, 'index'])->name('admin.profile');
            Route::get('/create', [AdminProfilAdminController::class, 'create'])->name('admin.admin.create');
            Route::post('/', [AdminProfilAdminController::class, 'store'])->name('admin.admin.store');
            Route::get('/{id}', [AdminProfilAdminController::class, 'show'])->name('admin.admin.show');
            Route::get('/{id}/edit', [AdminProfilAdminController::class, 'edit'])->name('admin.admin.edit');
            Route::put('/{id}', [AdminProfilAdminController::class, 'update'])->name('admin.admin.update');
            Route::delete('/{id}', [AdminProfilAdminController::class, 'destroy'])->name('admin.admin.destroy');
            Route::patch('/{id}/toggle-status', [AdminProfilAdminController::class, 'toggleStatus'])->name('admin.admin.toggle-status');
        });

        // Dosen
        Route::prefix('admin/pengguna/dosen')->group(function () {
            Route::get('/', [AdminProfilDosenController::class, 'index'])->name('admin.dosen.index');
            Route::get('/create', [AdminProfilDosenController::class, 'create'])->name('admin.dosen.create');
            Route::post('/', [AdminProfilDosenController::class, 'store'])->name('admin.dosen.store');
            Route::get('/{id}', [AdminProfilDosenController::class, 'show'])->name('admin.dosen.show');
            Route::get('/{id}/edit', [AdminProfilDosenController::class, 'edit'])->name('admin.dosen.edit');
            Route::put('/{id}', [AdminProfilDosenController::class, 'update'])->name('admin.dosen.update');
            Route::delete('/{id}', [AdminProfilDosenController::class, 'destroy'])->name('admin.dosen.destroy');
            Route::patch('/{id}/toggle-status', [AdminProfilDosenController::class, 'toggleStatus'])->name('admin.dosen.toggle-status');
        });

        // Mahasiswa
        Route::prefix('admin/pengguna/mahasiswa')->group(function () {
            Route::get('/', [AdminProfilMahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
            Route::get('/create', [AdminProfilMahasiswaController::class, 'create'])->name('admin.mahasiswa.create');
            Route::post('/', [AdminProfilMahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
            Route::get('/{id}', [AdminProfilMahasiswaController::class, 'show'])->name('admin.mahasiswa.show');
            Route::get('/{id}/edit', [AdminProfilMahasiswaController::class, 'edit'])->name('admin.mahasiswa.edit');
            Route::put('/{id}', [AdminProfilMahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
            Route::delete('/{id}', [AdminProfilMahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');
            Route::patch('/{id}/toggle-status', [AdminProfilMahasiswaController::class, 'toggleStatus'])->name('admin.mahasiswa.toggle-status');
            Route::get('/{id}/verify', [AdminProfilMahasiswaController::class, 'getDataVerifikasiMahasiswa'])->name('admin.mahasiswa.verify');
            Route::patch('/{id}/reject', [AdminProfilMahasiswaController::class, 'verfikasiMahasiswaReject'])->name('admin.mahasiswa.verify.reject');
            Route::patch('/{id}/verify', [AdminProfilMahasiswaController::class, 'verfikasiMahasiswa'])->name('admin.mahasiswa.verify');
        });

        // PROGRAM STUDI
        Route::prefix('admin/program_studi')->group(function () {
            Route::get('/', [ProgramStudiController::class, 'index'])->name('program_studi.index');
            Route::get('/create', [ProgramStudiController::class, 'create'])->name('program_studi.create');
            Route::post('/', [ProgramStudiController::class, 'store'])->name('program_studi.store');
            Route::get('/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program_studi.edit');
            Route::put('/{id}', [ProgramStudiController::class, 'update'])->name('program_studi.update');
            Route::delete('/{id}', [ProgramStudiController::class, 'destroy'])->name('program_studi.destroy');
        });

        // BIDANG INDUSTRI
        Route::get('/admin/bidang_industri/', [AdminBidangIndustriController::class, 'index'])->name('admin.bidang_industri.index');
        Route::get('/admin/bidang_industri/show/{id}', [AdminBidangIndustriController::class, 'show'])->name('admin.bidang_industri.show');
        Route::get('/admin/bidang_industri/create', [AdminBidangIndustriController::class, 'create'])->name('admin.bidang_industri.create');
        Route::post('/admin/bidang_industri/', [AdminBidangIndustriController::class, 'store'])->name('admin.bidang_industri.store');
        Route::get('/admin/bidang_industri/edit/{id}', [AdminBidangIndustriController::class, 'edit'])->name('admin.bidang_industri.edit');
        Route::put('/admin/bidang_industri/edit/{id}', [AdminBidangIndustriController::class, 'update'])->name('admin.bidang_industri.update');
        Route::delete('/admin/bidang_industri/delete/{id}', [AdminBidangIndustriController::class, 'destroy'])->name('admin.bidang_industri.destroy');

        // PERUSAHAAN MITRA
        Route::get('/admin/perusahaan/', [AdminPerusahaanMitraController::class, 'index'])->name('admin.perusahaan.index');
        Route::get('/admin/perusahaan/create', [AdminPerusahaanMitraController::class, 'create'])->name('admin.perusahaan.create');
        Route::post('/admin/perusahaan/', [AdminPerusahaanMitraController::class, 'store'])->name('admin.perusahaan.store');
        Route::get('/admin/perusahaan/{id}', [AdminPerusahaanMitraController::class, 'show'])->name('admin.perusahaan.show');
        Route::get('/admin/perusahaan/{id}/edit', [AdminPerusahaanMitraController::class, 'edit'])->name('admin.perusahaan.edit');
        Route::put('/admin/perusahaan/{id}', [AdminPerusahaanMitraController::class, 'update'])->name('admin.perusahaan.update');
        Route::delete('/admin/perusahaan/{id}', [AdminPerusahaanMitraController::class, 'destroy'])->name('admin.perusahaan.destroy');
        Route::patch('/admin/perusahaan/{id}/toggle-status', [AdminPerusahaanMitraController::class, 'toggleStatus'])->name('admin.perusahaan.toggle-status');

        // KATEGORI
        Route::get('/admin/kategori/', [AdminKategoriController::class, 'index'])->name('admin.keahlian.kategori.index');
        Route::get('/admin/kategori/create', [AdminKategoriController::class, 'create'])->name('admin.keahlian.kategori.create');
        Route::post('/admin/kategori/', [AdminKategoriController::class, 'store'])->name('admin.keahlian.kategori.store');
        Route::get('/admin/kategori/{id}', [AdminKategoriController::class, 'show'])->name('admin.keahlian.kategori.show');
        Route::get('/admin/kategori/{id}/edit', [AdminKategoriController::class, 'edit'])->name('admin.keahlian.kategori.edit');
        Route::put('/admin/kategori/{id}', [AdminKategoriController::class, 'update'])->name('admin.keahlian.kategori.update');
        Route::delete('/admin/kategori/{id}', [AdminKategoriController::class, 'destroy'])->name('admin.keahlian.kategori.destroy');

        // TAG KEAHLIAN
        Route::get('/admin/tag_keahlian/', [AdminTagKeahlianController::class, 'index'])->name('admin.keahlian.tag_keahlian.index');
        Route::get('/admin/tag_keahlian/create', [AdminTagKeahlianController::class, 'create'])->name('admin.keahlian.tag_keahlian.create');
        Route::post('/admin/tag_keahlian/', [AdminTagKeahlianController::class, 'store'])->name('admin.keahlian.tag_keahlian.store');
        Route::get('/admin/tag_keahlian/{id}', [AdminTagKeahlianController::class, 'show'])->name('admin.keahlian.tag_keahlian.show');
        Route::get('/admin/tag_keahlian/{id}/edit', [AdminTagKeahlianController::class, 'edit'])->name('admin.keahlian.tag_keahlian.edit');
        Route::put('/admin/tag_keahlian/{id}', [AdminTagKeahlianController::class, 'update'])->name('admin.keahlian.tag_keahlian.update');
        Route::delete('/admin/tag_keahlian/{id}', [AdminTagKeahlianController::class, 'destroy'])->name('admin.keahlian.tag_keahlian.destroy');


        // MAGANG: Keagiatan
        Route::get('/admin/magang/', function () {
            return redirect()->route('admin.index');
        });
        Route::get('/admin/magang/kegiatan', [AdminMagangController::class, 'kegiatan'])->name('admin.magang.kegiatan');
        Route::get('/admin/magang/kegiatan/{pengajuan_id}/detail', [AdminMagangController::class, 'kegiatanDetail'])->name('admin.magang.kegiatan.detail');
        Route::get('/admin/magang/kegiatan/{dosen_id}/getDosenData', [AdminMagangController::class, 'getDosenData'])->name('admin.magang.kegiatan.getDosenData');
        Route::put('/admin/magang/kegiatan', [AdminMagangController::class, 'kegiatanPost'])->name('admin.magang.kegiatan.update');
        Route::delete('/admin/magang/kegiatan/upload/keterangan/', [AdminMagangController::class, 'deleteKeterangan'])->name('admin.magang.kegiatan.delete.keterangan');
        Route::put('/admin/magang/kegiatan/update/catatan', [AdminMagangController::class, 'updateCatatan'])->name('admin.magang.kegiatan.update.catatan');

        // MAGANG: Lowongan
        Route::prefix('admin/magang/lowongan')->group(function () {
            Route::get('/', [AdminLowonganMagangController::class, 'index'])->name('admin.magang.lowongan.index');
            Route::get('/create', [AdminLowonganMagangController::class, 'create'])->name('admin.magang.lowongan.create');
            Route::post('/', [AdminLowonganMagangController::class, 'store'])->name('admin.magang.lowongan.store');
            Route::get('/{id}/lanjutan', [AdminLowonganMagangController::class, 'formLanjutan'])->name('admin.magang.lowongan.lanjutan');
            Route::post('/{id}/lanjutan', [AdminLowonganMagangController::class, 'storeLanjutan'])->name('admin.magang.lowongan.lanjutan.store');
            Route::get('/{id}', [AdminLowonganMagangController::class, 'show'])->name('admin.magang.lowongan.show');
            Route::get('/{id}/edit', [AdminLowonganMagangController::class, 'edit'])->name('admin.magang.lowongan.edit');
            Route::put('/{id}', [AdminLowonganMagangController::class, 'update'])->name('admin.magang.lowongan.update');
            Route::delete('/{id}', [AdminLowonganMagangController::class, 'destroy'])->name('admin.magang.lowongan.destroy');

            Route::patch('/{id}/toggle-status', [AdminLowonganMagangController::class, 'toggleStatus'])->name('admin.magang.lowongan.toggle-status');
            Route::patch('/{id}/deactivate', [AdminLowonganMagangController::class, 'deactivateForBack'])->name('admin.magang.lowongan.deactivate');
        });

        // MAGANG: Periode Lowongan
        Route::prefix('admin/magang/periode')->group(function () {
            Route::get('/', [AdminManajemenPeriodeController::class, 'index'])->name('admin.manajemen_periode.index');
            Route::get('/{id}/edit', [AdminManajemenPeriodeController::class, 'edit'])->name('admin.manajemen_periode.edit');
            Route::put('/{id}', [AdminManajemenPeriodeController::class, 'update'])->name('admin.manajemen_periode.update');
        });

        // STATISTIK
        Route::get('/admin/statistik', [AdminStatistikController::class, 'index'])->name('admin.statistik');
        Route::get('/admin/statistik/get/MagangVsTidak', [AdminStatistikController::class, 'getMagangVsTidak'])->name('admin.statistik.get.MagangVsTidak');
        Route::get('/admin/statistik/excel/MagangVsTidak', [AdminStatistikController::class, 'excelMagangVsTidak'])->name('admin.statistik.excel.MagangVsTidak');
        Route::get('/admin/statistik/get/TrenPeminatanMahasiswa', [AdminStatistikController::class, 'getTrenPeminatanMahasiswa'])->name('admin.statistik.get.TrenPeminatanMahasiswa');
        Route::get('/admin/statistik/excel/TrenPeminatanMahasiswa', [AdminStatistikController::class, 'excelTrenPeminatanMahasiswa'])->name('admin.statistik.excel.TrenPeminatanMahasiswa');
        Route::get('/admin/statistik/get/JumlahDosenPembimbing', [AdminStatistikController::class, 'getJumlahDosenPembimbing'])->name('admin.statistik.get.JumlahDosenPembimbing');
        Route::get('/admin/statistik/excel/JumlahDosenPembimbing', [AdminStatistikController::class, 'excelJumlahDosenPembimbing'])->name('admin.statistik.excel.JumlahDosenPembimbing');

        // EVALUASI: SPK
        Route::get('/admin/evaluasi/spk', [AdminEvaluasiSPKController::class, 'index'])->name('admin.evaluasi.spk');
        Route::get('/admin/evaluasi/spk/feedback', [AdminEvaluasiSPKController::class, 'feedback'])->name('admin.evaluasi.spk.feedback');
        Route::patch('/admin/evaluasi/spk/feedback/markReadAll', [AdminEvaluasiSPKController::class, 'markReadAll'])->name('admin.evaluasi.spk.feedback.markReadAll');
        Route::get('/admin/evaluasi/spk/feedback/show/{feedback_spk_id}', [AdminEvaluasiSPKController::class, 'showFeedback'])->name('admin.evaluasi.spk.feedback.show');
        Route::get('/admin/evaluasi/spk/feedback/excel', [AdminEvaluasiSPKController::class, 'excelFeedback'])->name('admin.evaluasi.spk.feedback.excel');
        Route::get('/admin/evaluasi/spk/detail', [AdminEvaluasiSPKController::class, 'spk'])->name('admin.evaluasi.spk.detail');
        Route::get('/admin/evaluasi/spk/lowongan', [AdminEvaluasiSPKController::class, 'lowongan'])->name('admin.evaluasi.spk.lowongan');
        Route::get('/admin/evaluasi/spk/profileTesting', [AdminEvaluasiSPKController::class, 'profileTesting'])->name('admin.evaluasi.spk.profile-testing');
        Route::put('/admin/evaluasi/spk/updateProfileTesting', [AdminEvaluasiSPKController::class, 'updateProfileTesting'])->name('admin.evaluasi.spk.profile-testing.update');
        Route::put('/admin/evaluasi/spk/update', [AdminEvaluasiSPKController::class, 'update'])->name('admin.evaluasi.spk.update');
    });

    Route::middleware(['authorize:dosen'])->group(function () {
        Route::get('/dosen', [DosenController::class, 'dashboardDosen']);
        Route::get('/dosen/notifikasi', [NotificationController::class, 'index'])->name('dosen.notifikasi');
        Route::get('/dosen/mahasiswabimbingan', [DosenController::class, 'tampilMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan');
        Route::get('/dosen/mahasiswabimbingan/{id}/logAktivitas', [DosenController::class, 'logAktivitas'])->name('dosen.detail.logAktivitas');
        Route::get('/dosen/mahasiswabimbingan/{id}/detail', [DosenController::class, 'detailMahasiswaBimbingan'])->name('dosen.mahasiswabimbingan.detail');
        Route::get('/dosen/mahasiswabimbingan/{id}/logAktivitas', [DosenController::class, 'logAktivitas'])->name('dosen.detail.logAktivitas');
        Route::get('/dosen/profile', [DosenController::class, 'profile'])->name('dosen.profile');
        Route::get('/dosen/profile/edit', [DosenController::class, 'editProfile'])->name('dosen.edit-profil');
        Route::post('/dosen/profile/update', [DosenController::class, 'updateProfile'])->name('dosen.update-profil');
        Route::post('/dosen/profile/update-password', [DosenController::class, 'changePassword'])->name('dosen.profile.update-password');
        Route::post('/dosen/logaktivitas/feedback', [DosenController::class, 'simpanFeedback'])->name('dosen.logaktivitas.feedback');
        Route::post('/log-aktivitas/hapus-feedback', [DosenController::class, 'hapusFeedback'])->name('dosen.logaktivitas.hapusFeedback');
        Route::get('/logaktivitas/export/{pengajuan_id}', [DosenController::class, 'export_excel'])->name('dosen.logaktivitas.export');
    });

    Route::middleware(['authorize:mahasiswa'])->group(function () {
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/mahasiswa/notifikasi', [NotificationController::class, 'index'])->name('mahasiswa.notifikasi');
        // PROFILE
        Route::get('/mahasiswa/profile', [MahasiswaAkunProfilController::class, 'profile'])->name('mahasiswa.profile');
        Route::get('/mahasiswa/profile/edit', [MahasiswaAkunProfilController::class, 'profile'])->name('mahasiswa.profile.edit');
        Route::put('/mahasiswa/profile/update', [MahasiswaAkunProfilController::class, 'update'])->name('mahasiswa.profile.update');
        Route::put('/mahasiswa/profile/update-password', [MahasiswaAkunProfilController::class, 'changePassword'])->name('mahasiswa.profile.update-password');
        Route::get('/mahasiswa/dokumen', [MahasiswaAkunProfilController::class, 'dokumen'])->name('mahasiswa.dokumen');
        Route::put('/mahasiswa/dokumen/upload/cv', [MahasiswaAkunProfilController::class, 'dokumenUploadCV'])->name('mahasiswa.dokumen.upload.cv');
        Route::put('/mahasiswa/dokumen/upload/transkripNilai', [MahasiswaAkunProfilController::class, 'dokumenUploadtranskripNilai'])->name('mahasiswa.dokumen.upload.transkripNilai');

        // MAGANG
        Route::get('/mahasiswa/magang/spkdd', [MahasiswaMagangController::class, 'SPKDD'])->name('mahasiswa.magang.SPKDD');
        Route::get('/mahasiswa/magang', [MahasiswaMagangController::class, 'index'])->name('mahasiswa.magang');
        Route::get('/mahasiswa/magang/lowongan/', function () {
            return redirect('/mahasiswa/magang');
        });
        Route::get('/mahasiswa/magang/lowongan/{lowongan_id}/detail', [MahasiswaMagangController::class, 'magangDetail'])->name('mahasiswa.magang.lowongan.detail');
        Route::get('/mahasiswa/magang/lowongan/{lowongan_id}/ajukan', [MahasiswaMagangController::class, 'ajukan'])->name('mahasiswa.magang.lowongan.ajukan');
        Route::post('/mahasiswa/magang/lowongan/{lowongan_id}/ajukan', [MahasiswaMagangController::class, 'ajukanPost'])->name('mahasiswa.magang.lowongan.ajukan.post');
        // PENGAJUAN
        Route::get('/mahasiswa/magang/pengajuan', [MahasiswaPengajuanController::class, 'index'])->name('mahasiswa.magang.pengajuan');
        Route::get('/mahasiswa/magang/pengajuan/{pengajuan_id}/detail', [MahasiswaPengajuanController::class, 'pengajuanDetail'])->name('mahasiswa.magang.pengajuan.detail');
        Route::delete('/mahasiswa/magang/pengajuan/{pengajuan_id}/delete', [MahasiswaPengajuanController::class, 'pengajuanDelete'])->name('mahasiswa.magang.pengajuan.delete');
        Route::put('/mahasiswa/magang/pengajuan/{pengajuan_id}/uploadHasil', [MahasiswaPengajuanController::class, 'uploadHasil'])->name('mahasiswa.magang.pengajuan.uploadHasil');
        // LOG AKTIVITAS
        Route::get('/mahasiswa/magang/pengajuan/log-aktivitas', function () {
            return redirect()->route('mahasiswa.magang.pengajuan');
        });
        Route::get('/mahasiswa/magang/pengajuan/log-aktivitas/{pengajuan_id}', [MahasiswaPengajuanController::class, 'logAktivitas'])->name('mahasiswa.magang.log-aktivitas');
        Route::get('/mahasiswa/magang/pengajuan/log-aktivitas/{pengajuan_id}/data', [MahasiswaPengajuanController::class, 'logAktivitasData'])->name('mahasiswa.magang.log-aktivitas.data');
        Route::put('/mahasiswa/magang/pengajuan/log-aktivitas/{pengajuan_id}', [MahasiswaPengajuanController::class, 'logAktivitasUpdate'])->name('mahasiswa.magang.log-aktivitas.update');
        Route::get('/mahasiswa/magang/pengajuan/log-aktivitas/{pengajuan_id}/excel', [MahasiswaPengajuanController::class, 'logAktivitasExcel'])->name('mahasiswa.magang.log-aktivitas.excel');
        // FEEDBACK
        Route::get('/mahasiswa/magang/pengajuan/feedback-lowongan/{pengajuan_id}', [MahasiswaPengajuanController::class, 'feedback'])->name('mahasiswa.magang.feedback');
        Route::put('/mahasiswa/magang/pengajuan/feedback-lowongan/{pengajuan_id}', [MahasiswaPengajuanController::class, 'feedbackPost'])->name('mahasiswa.magang.feedback.update');
        // FEEDBACK: SPK
        Route::get('/mahasiswa/evaluasi', function () {
            return redirect()->route('mahasiswa.evaluasi.feedback.spk');
        });
        Route::get('/mahasiswa/evaluasi/spk', [MahasiswaController::class, 'feedbackSpk'])->name('mahasiswa.evaluasi.feedback.spk');
        Route::put('/mahasiswa/evaluasi/spk', [MahasiswaController::class, 'setFeedbackSPK'])->name('mahasiswa.evaluasi.feedback.spk.update');
    });

    // FEEDBACK MAHASISWA LOWONGAN
    Route::get('/admin/magang/lowongan/{id}/feedback', [AdminLowonganMagangController::class, 'feedback'])->name('admin.magang.lowongan.feedback');
});
