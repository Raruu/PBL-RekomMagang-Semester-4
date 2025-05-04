<?php

namespace App\Http\Controllers;

use App\Models\Keahlian;
use App\Models\KeahlianMahasiswa;
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
            'tipe_kerja_preferensi' => PreferensiMahasiswa::TIPE_KERJA_PREFERENSI,
            'keahlian_mahasiswa' => KeahlianMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('keahlian')->get(),
            'tingkat_kemampuan' => KeahlianMahasiswa::TINGKAT_KEMAMPUAN,
            'keahlian' => Keahlian::all(),
        ];

        if (str_contains($request->url(), '/edit')) {
            return view('mahasiswa.profile.profile-edit', $data);
        }
        return view('mahasiswa.profile.profile', $data);
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'nomor_telepon' => ['required', 'numeric', 'digits_between:10,20'],
                'alamat' => ['required', 'string'],
                'industri_preferensi' => ['required', 'string'],
                'posisi_preferensi' => ['required', 'string'],
                'tipe_kerja_preferensi' => ['required', 'string', 'in:onsite,remote,hybrid,semua'],
                'profile_picture' => ['nullable', 'image', 'max:2048'],
                'lokasi_alamat' => ['required', 'string'],
                'location_latitude' => ['required', 'numeric'],
                'location_longitude' => ['required', 'numeric'],
                'email' => ['required', 'string', 'email', 'max:100'],
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
                $profilMahasiswa = ProfilMahasiswa::where('mahasiswa_id', $user->user_id)->first();
                $profilMahasiswa->update($profilData);

                $preferensiMahasiswa = $profilMahasiswa->preferensiMahasiswa;
                $preferensiMahasiswa->update($preferensiData);

                $preferensiMahasiswa->lokasi->update([
                    'alamat' => $request->lokasi_alamat,
                    'latitude' => $request->location_latitude,
                    'longitude' => $request->location_longitude
                ]);

                $keahlianNew = [];
                $levels = array_keys(KeahlianMahasiswa::TINGKAT_KEMAMPUAN);
                foreach ($levels as $level) {
                    $keahlian = collect(json_decode($request->input("keahlian-{$level}"), true))->pluck('value')->toArray();
                    foreach ($keahlian as $keahlianNama) {
                        $keahlianNew[] = $keahlianNama;
                        $keahlianRecord = Keahlian::where('nama_keahlian', $keahlianNama)->first();
                        KeahlianMahasiswa::updateOrCreate(
                            ['mahasiswa_id' => $user->user_id, 'keahlian_id' => $keahlianRecord->keahlian_id],
                            ['tingkat_kemampuan' => $level]
                        );
                    }
                }

                $keahlianOld = KeahlianMahasiswa::where('mahasiswa_id', $user->user_id)
                    ->get()->pluck('keahlian.nama_keahlian')->toArray();
                $toDeleteKeahlian = array_diff($keahlianOld, $keahlianNew);
                if (!empty($toDeleteKeahlian)) {
                    $keahlianIdsToDelete = Keahlian::whereIn('nama_keahlian', $toDeleteKeahlian)->pluck('keahlian_id');            
                    KeahlianMahasiswa::where('mahasiswa_id', $user->user_id)
                        ->whereIn('keahlian_id', $keahlianIdsToDelete)                 
                        ->delete();
                }
               

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

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:5'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }
        Auth::user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }

    public function dokumen()
    {
        $user = ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('user')->first();
        return view('mahasiswa.dokumen', [
            'user' => $user
        ]);
    }

    public function dokumenUpload(Request $request)
    {
        $rules = [
            'dokumen_cv' => ['required', 'file', 'max:8192'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $dokumenCv = $request->file('dokumen_cv');
            $dokumenCvName = 'dokumen-cv-' . Auth::user()->username . '.pdf';
            $dokumenCv->storeAs('public/dokumen/mahasiswa/', $dokumenCvName);

            ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->update([
                'file_cv' => $dokumenCvName
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Dokumen terupload'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ]);
        }
    }
}
