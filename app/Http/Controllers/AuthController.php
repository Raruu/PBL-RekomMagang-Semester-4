<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\PreferensiMahasiswa;
use App\Models\ProfilMahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal, cek username dan password',
            ]);
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $prodi = ProgramStudi::all();
        return view('auth.register', ['prodi' => $prodi]);
    }

    public function postregister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => ['required', 'string', 'min:3', 'unique:user,username'],
                'nama' => ['required', 'string', 'max:100'],
                'password' => ['required', 'min:5'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:user,email'],
                'program_id' => ['required', 'exists:program_studi,program_id']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $dataUser = $request->only([
                'username',
                'password',
                'email'
            ]);
            $dataUser['role'] = 'mahasiswa';
            $user = User::create($dataUser);

            $lokasi = Lokasi::create([]);

            $dataMahasiswa = $request->only([
                'nama',
                'program_id',
            ]);
            $dataMahasiswa['lokasi_id'] = $lokasi->lokasi_id;
            $dataMahasiswa['semester'] = 6;
            $dataMahasiswa['mahasiswa_id'] = $user->user_id;
            $lastNim = ProfilMahasiswa::orderBy('nim', 'desc')->value('nim');
            $dataMahasiswa['nim'] = $lastNim ? (int)$lastNim + 1 : 1;
            ProfilMahasiswa::create($dataMahasiswa);

            PreferensiMahasiswa::create([
                'mahasiswa_id' => $user->user_id,
                'lokasi_id' => $lokasi->lokasi_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
            ]);
        }
    }
}
