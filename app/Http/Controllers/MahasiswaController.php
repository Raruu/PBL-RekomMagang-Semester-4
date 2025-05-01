<?php

namespace App\Http\Controllers;

use App\Models\PreferensiMahasiswa;
use App\Models\ProfilMahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dashboard');
    }

    public function profile(Request $request)
    {
        $user = ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)
            ->with('user')->with('programStudi')->first();

        $data = [
            'user' => $user,
        ];

        if (str_contains($request->url(), '/edit')) {
            return view('mahasiswa.profile.profile-edit', $data);
        }
        return view('mahasiswa.profile.profile', [
            'user' => $user,
            $data
        ]);
    }

    public function update(Request $request)
    {

        $rules = [
            'nomor_telepon' => ['required', 'numeric'],
            'alamat' => ['required', 'string'],
            'industri_preferensi' => ['required', 'string'],
            'lokasi_preferensi' => ['required', 'string'], //
            'posisi_preferensi' => ['required', 'string'],
            'tipe_kerja_preferensi' => ['required', 'string', 'in:onsite,remote,hybrid,semua'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:5', 'confirmed'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        if ($user) {
            if (!$request->filled('password')) {
                $request->request->remove('password');
            }

            $userData = $profilData = $request->only(['email']);
            $profilData = $request->only([
                'lokasi_id',
                // 'nama_lengkap',
                'nomor_telepon',
                'alamat',
            ]);
            $preferensiData = $request->only([
                'industri_preferensi',
                'lokasi_preferensi',
                'posisi_preferensi',
                'tipe_kerja_preferensi',
            ]);

            if ($request->hasFile('profile_picture')) {
                $image = $request->file('profile_picture');
                $imageName = 'profile-' . $user->username . '.webp';
                $image->storeAs('public/profile_pictures', $imageName);
                $profilData['foto_profil'] = $imageName;
            }

            $user->update($userData);
            ProfilMahasiswa::where('mahasiswa_id', $user->user_id)->update($profilData);
            PreferensiMahasiswa::where('mahasiswa_id', $user->user_id)->update($preferensiData);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
