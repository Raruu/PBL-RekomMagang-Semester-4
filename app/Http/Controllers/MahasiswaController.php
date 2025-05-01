<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\PreferensiMahasiswa;
use App\Models\ProfilMahasiswa;
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
            ->with('user')->with('programStudi')->with('preferensiMahasiswa')->first();

        $data = [
            'user' => $user,
            'tipe_kerja_preferensi' => PreferensiMahasiswa::TIPE_KERJA_PREFERENSE
        ];

        if (str_contains($request->url(), '/edit')) {
            return view('mahasiswa.profile.profile-edit', $data);
        }
        return view('mahasiswa.profile.profile', $data);
    }

    public function update(Request $request)
    {
        try {
            //code...

            $rules = [
                'nomor_telepon' => ['required', 'numeric'],
                'alamat' => ['required', 'string'],
                'industri_preferensi' => ['required', 'string'],
                'posisi_preferensi' => ['required', 'string'],
                'tipe_kerja_preferensi' => ['required', 'string', 'in:onsite,remote,hybrid,semua'],
                'profile_picture' => ['nullable', 'image', 'max:2048'],
                'lokasi_alamat' => ['required', 'string'],
                'location_latitude' => ['required', 'numeric'],
                'location_longitude' => ['required', 'numeric'],
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
                    'nomor_telepon',
                    'alamat',
                ]);
                $preferensiData = $request->only([
                    'industri_preferensi',
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
                $preferensiMahasiswa = PreferensiMahasiswa::where('mahasiswa_id', $user->user_id)->first();
                $preferensiMahasiswa->update($preferensiData);

                Lokasi::where('lokasi_id', $preferensiMahasiswa->lokasi->lokasi_id)->update([
                    'alamat' => $request->lokasi_alamat,
                    'latitude' => $request->location_latitude,
                    'longitude' => $request->location_longitude
                ]);

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
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
