<?php

namespace App\Http\Controllers;

use App\Models\PreferensiMahasiswa;
use App\Models\ProfilMahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.dashboard');
    }

    public function profile()
    {
        $user = ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)
            ->with('user')->with('programStudi')->first();
        return view('mahasiswa.profile.profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        // $rules = [

        // ];

        $user = Auth::user();
        if ($user) {
            if (!$request->filled('password')) {
                $request->request->remove('password');
            }

            $userData = $profilData = $request->only(['email']);
            $profilData = $request->only([
                'lokasi_id',
                'nama_lengkap',
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
