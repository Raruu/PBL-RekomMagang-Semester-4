<?php

namespace App\Http\Controllers;

use App\Models\BobotSPK;
use App\Models\FeedBackSpk;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\PreferensiMahasiswa;
use App\Models\ProfilMahasiswa;
use App\Models\User;
use App\Services\LocationService;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminEvaluasiSPKController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $feedback = FeedBackSpk::all();
            return DataTables::of($feedback)
                ->addIndexColumn()
                ->addColumn('feedback_spk_id', function ($row) {
                    return $row->feedback_spk_id;
                })
                ->addColumn('angkatan', function ($row) {
                    return $row->profilMahasiswa->angkatan;
                })
                ->addColumn('mahasiswa', function ($row) {
                    return $row->profilMahasiswa->nama;
                })
                ->addColumn('rating', function ($row) {
                    return $row->rating;
                })
                ->addColumn('feedback', function ($row) {
                    return $row->komentar;
                })
                ->make(true);
        }
        $bobotSpk = BobotSPK::pluck('bobot', 'jenis_bobot')->toArray();
        return view('admin.spk.index', ['spk' => $bobotSpk]);
    }

    public function showFeedback($feedback_id)
    {
        $feedback = FeedBackSpk::findOrFail($feedback_id);
        return view('admin.spk.show-feedback', [
            'feedback' => $feedback,
            'profilMahasiswa' => $feedback->profilMahasiswa
        ]);
    }

    public function spk(Request $request)
    {
        if ($request->ajax()) {
            $weights = [
                'IPK' => $request->input('bobot_ipk'),
                'keahlian' => $request->input('bobot_skill'),
                'pengalaman' => $request->input('bobot_pengalaman'),
                'jarak' => $request->input('bobot_jarak'),
                'posisi' => $request->input('bobot_posisi'),
            ];

            $score = SPKService::getRecommendations(User::where('username', '0000000000')->pluck('user_id'), $weights);
            return DataTables::of($score)
                ->addIndexColumn()
                ->addColumn('lowongan_id', function ($row) {
                    return $row['lowongan']->lowongan_id;
                })
                ->addColumn('skor', function ($row) {
                    return number_format($row['score'], 4);
                })
                ->addColumn('judul', function ($row) {
                    return $row['lowongan']->judul_lowongan;
                })
                ->make(true);
        }
        $bobotSpk = BobotSPK::pluck('bobot', 'jenis_bobot')->toArray();
        return view('admin.spk.edit-bobot', ['spk' => $bobotSpk]);
    }

    public function lowongan(Request $request)
    {
        $weights = [
            'IPK' => $request->input('bobot_ipk'),
            'keahlian' => $request->input('bobot_skill'),
            'pengalaman' => $request->input('bobot_pengalaman'),
            'jarak' => $request->input('bobot_jarak'),
            'posisi' => $request->input('bobot_posisi'),
        ];
        $user = User::where('username', '0000000000')->first();
        $lowonganMagang =  collect(SPKService::getRecommendations($user->user_id, $weights));
        $lowonganMagang = $lowonganMagang->firstWhere('lowongan.lowongan_id', $request->input('lowongan_id'));
        $lowongan = $lowonganMagang['lowongan'];
        $score = $lowonganMagang['score'];

        $lokasi = $lowongan->lokasi;
        $preferensiLokasi = ProfilMahasiswa::find($user->user_id)->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));

        return view('admin.spk.detail-lowongan', [
            'lowongan' => $lowongan,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'score' => $score,
            'lokasi' => $lokasi,
            'jarak' => LocationService::haversineDistance(
                $lokasi->latitude,
                $lokasi->longitude,
                $preferensiLokasi->latitude,
                $preferensiLokasi->longitude
            ),
            'days' => $diff->format('%r%a'),
        ]);
    }

    public function profileTesting()
    {
        $user = User::where('username', '0000000000')->first();
        $profileMahasiswa = ProfilMahasiswa::where('mahasiswa_id', $user->user_id)
            ->with(['user', 'programStudi', 'preferensiMahasiswa', 'pengalamanMahasiswa'])
            ->first();
        $data = [
            'user' => $profileMahasiswa,
            'tipe_kerja_preferensi' => PreferensiMahasiswa::TIPE_KERJA_PREFERENSI,
            'keahlian_mahasiswa' => KeahlianMahasiswa::where('mahasiswa_id', $user->user_id)->with('keahlian')->get(),
            'tingkat_kemampuan' => KeahlianMahasiswa::TINGKAT_KEMAMPUAN,
            'keahlian' => Keahlian::all(),
            'on_complete' => request()->query('on_complete')
        ];
        return view('admin.spk.profile-testing', $data);
    }

    public function updateProfileTesting(Request $request)
    {
        try {
            $rules = [
                'nomor_telepon' => ['required', 'numeric', 'digits_between:10,20'],
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
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            $user = User::where('username', '0000000000')->first();
            if ($user) {
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $userData = $profilData = $request->only(['email']);

                $profilData = $request->only([
                    'nomor_telepon',
                    'angkatan',
                    'ipk'
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

                $submittedExperienceIds = [];
                foreach ($request->input('nama_pengalaman', []) as $index => $nama_pengalaman) {
                    $pengalamanMahasiswa = $profilMahasiswa->pengalamanMahasiswa()->updateOrCreate(
                        ['nama_pengalaman' => $nama_pengalaman],
                        [
                            'deskripsi_pengalaman' => $request->input('deskripsi_pengalaman')[$index],
                            'tipe_pengalaman' => $request->input('tipe_pengalaman')[$index],
                            'periode_mulai' => $request->input('periode_mulai')[$index],
                            'periode_selesai' => $request->input('periode_selesai')[$index],
                        ]
                    );

                    $submittedExperienceIds[] = $pengalamanMahasiswa->pengalaman_id;

                    // Process and sync tags
                    $tags = collect(json_decode($request->input('tag')[$index], true))->pluck('value')->toArray();
                    $keahlianIds = [];
                    foreach ($tags as $tagName) {
                        $keahlian = Keahlian::where(['nama_keahlian' => $tagName])->first();
                        $keahlianIds[] = $keahlian->keahlian_id;
                    }

                    // Sync many-to-many
                    $pengalamanMahasiswa->pengalamanTagBelongsToMany()->sync($keahlianIds);

                    if ($request->hasFile('dokumen_file') && isset($request->file('dokumen_file')[$index])) {
                        $dokumenName = 'dokumen-pengalaman-' . $user->username . '.pdf';
                        $request->file('dokumen_file')[$index]->storeAs('public/dokumen/mahasiswa/', $dokumenName);
                        $pengalamanMahasiswa->path_file = $dokumenName;
                        $pengalamanMahasiswa->save();
                    }
                }

                if (!empty($submittedExperienceIds)) {
                    $profilMahasiswa->pengalamanMahasiswa()
                        ->whereNotIn('pengalaman_id', $submittedExperienceIds)
                        ->delete();
                }

                DB::commit();

                return response()->json([
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'message' => 'Data tidak ditemukan'
                ], 422);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'bobot_ipk' => 'required',
                'bobot_skill' => 'required',
                'bobot_pengalaman' => 'required',
                'bobot_jarak' => 'required',
                'bobot_posisi' => 'required',
            ]);

            $totalBobot = $request->input('bobot_ipk') +
                $request->input('bobot_skill') +
                $request->input('bobot_pengalaman') +
                $request->input('bobot_jarak') +
                $request->input('bobot_posisi');


            if ($totalBobot > 1.0) {
                return response()->json(['message' => 'Max Total Bobot adalah 1'], 422);
            }

            if ($validator->fails()) {
                return response()->json(['message' => 'Data tidak lengkap'], 422);
            }

            $bobot = $request->input('bobot');

            BobotSPK::updateOrCreate(['jenis_bobot' => 'IPK'], ['bobot' => $request->input('bobot_ipk')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'keahlian'], ['bobot' => $request->input('bobot_skill')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'pengalaman'], ['bobot' => $request->input('bobot_pengalaman')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'jarak'], ['bobot' => $request->input('bobot_jarak')]);
            BobotSPK::updateOrCreate(['jenis_bobot' => 'posisi'], ['bobot' => $request->input('bobot_posisi')]);

            DB::commit();
            return response()->json(['message' => 'Bobot berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
