<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/' . Auth::user()->getRole());
    });

    Route::middleware(['authorize:admin'])->group(function () {
        Route::get('/admin', function () {
            return view('welcome');
        });
    });

    Route::middleware(['authorize:dosen'])->group(function () {
        Route::get('/dosen', function () {
            return view('welcome');
        });
    });

    Route::middleware(['authorize:mahasiswa'])->group(function () {
        Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
        Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile']);
        Route::post('/mahasiswa/profile/update', [MahasiswaController::class, 'update']);
    });
});
