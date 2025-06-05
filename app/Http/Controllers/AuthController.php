<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\LowonganMagang;
use App\Models\PreferensiMahasiswa;
use App\Models\ProfilAdmin;
use App\Models\ProfilMahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            if (filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
                $credentials['email'] = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL);
                unset($credentials['username']);
                $isActive = User::where('email', $credentials['email'])->select('is_active')->first();
            } else {
                $isActive = User::where('username', $credentials['username'])->select('is_active')->first();
            }

            if ($isActive != null && !$isActive->is_active) {
                return response()->json([
                    'message' => 'User ini tidak Aktif',
                ], 422);
            }

            if (Auth::attempt($credentials)) {
                if (Auth::user()->role != 'dosen') {
                    LowonganMagang::where('batas_pendaftaran', '<', date('Y-m-d'))->update(['is_active' => 0]);
                }
                return response()->json([
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'message' => 'Login Gagal, cek username dan password',
            ], 422);
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            foreach (ProfilAdmin::with('user')->get() as $admin) {
                $admin->user->readNotifications()->delete();
            }
        } else {
            Auth::user()->readNotifications()->delete();
        }
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
                'username' => ['required', 'string', 'min:3', 'max:20', 'unique:user,username'],
                'nama' => ['required', 'string', 'max:100'],
                'password' => ['required', 'min:5'],
                'email' => ['required', 'string', 'email', 'max:100', 'unique:user,email'],
                'program_id' => ['required', 'exists:program_studi,program_id']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                    'msgField' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $dataUser = $request->only([
                    'username',
                    'password',
                    'email'
                ]);
                $dataUser['role'] = 'mahasiswa';
                $user = User::create($dataUser);

                $dataMahasiswa = $request->only([
                    'nama',
                    'program_id',
                ]);

                $profilMahasiswaLokasi = Lokasi::create([]);
                $dataMahasiswa['lokasi_id'] = $profilMahasiswaLokasi->lokasi_id;
                $dataMahasiswa['mahasiswa_id'] = $user->user_id;
                $dataMahasiswa['nim'] = $user->username;
                ProfilMahasiswa::create($dataMahasiswa);

                $lokasi = Lokasi::create([]);
                PreferensiMahasiswa::create([
                    'mahasiswa_id' => $user->user_id,
                    'lokasi_id' => $lokasi->lokasi_id
                ]);

                DB::commit();
                return response()->json([
                    'message' => 'Data user berhasil disimpan',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Kesalahan pada Server',
                    'console' => $th->getMessage(),
                ], 500);
            }
        }
    }
}
