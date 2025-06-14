<?php

namespace App\Http\Controllers;

use App\Models\BidangIndustri;
use App\Models\DokumenPengajuan;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\LowonganMagang;
use App\Models\PengajuanMagang;
use App\Models\ProfilMahasiswa;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\LocationService;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaMagangController extends Controller
{
    public function index(Request $request)
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        if ($request->ajax()) {
            $lowonganMagang = SPKService::getRecommendations(Auth::user()->user_id);
            $request->session()->put('lowonganMagang', $lowonganMagang);
            return DataTables::of($lowonganMagang)
                ->addColumn('lowongan_id', function ($row) {
                    return $row['lowongan']->lowongan_id;
                })
                ->addColumn('skor', function ($row) {
                    return number_format($row['score'], 4);
                })
                ->addColumn('judul', function ($row) {
                    return $row['lowongan']->judul_lowongan;
                })
                ->addColumn('tipe_kerja_lowongan', function ($row) {
                    return $row['lowongan']->tipe_kerja_lowongan;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row['lowongan']->deskripsi;
                })
                ->addColumn('batas_pendaftaran', function ($row) {
                    $diff = date_diff(
                        date_create(date('Y-m-d')),
                        date_create($row['lowongan']->batas_pendaftaran)
                    );
                    $days = $diff->format('%a');
                    return $diff->invert ? "-$days" : $days;
                })
                ->addColumn('gaji', function ($row) {
                    return $row['lowongan']->gaji;
                })
                ->addColumn('keahlian_lowongan', function ($row) {
                    $keahlian = '';
                    foreach ($row['lowongan']->keahlianLowongan as $keahlianLowongan) {
                        $keahlian .= $keahlianLowongan->keahlian->nama_keahlian . ', ';
                    }
                    return rtrim($keahlian, ', ');
                })
                ->addColumn('is_diajukan', function ($row) {
                    return PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->where('lowongan_id', $row['lowongan']->lowongan_id)->exists();
                })
                ->addColumn('bidang_industri', function ($row) {
                    return $row['lowongan']->perusahaanMitra->bidangIndustri->nama;
                })
                ->make(true);
        }
        return view('mahasiswa.magang.index', [
            'keahlian' => Keahlian::all(),
            'tipeKerja' => LowonganMagang::TIPE_KERJA,
            'mahasiswa' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
            'bidangIndustri' => BidangIndustri::all(),
        ]);
    }

    public function magangDetail($lowongan_id)
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        $lowonganMagang = collect(session('lowonganMagang') ?: SPKService::getRecommendations(Auth::user()->user_id));
        $lowonganMagang = $lowonganMagang->firstWhere('lowongan.lowongan_id', $lowongan_id);

        if ($lowonganMagang == null) {
            return redirect()->route('mahasiswa.magang');
        }

        $lowongan = $lowonganMagang['lowongan'];
        $score = $lowonganMagang['score'];
        $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->where('lowongan_id', $lowongan_id)->value('pengajuan_id');
        $lokasi = $lowongan->lokasi;
        $preferensiLokasi = Auth::user()->profilMahasiswa->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));

        if ($lowongan->is_active == 0 && $pengajuanMagang) {
            return redirect()->route('mahasiswa.magang.pengajuan.detail', ['pengajuan_id' => $pengajuanMagang]);
        } else if ($lowongan->is_active == 0 && !$pengajuanMagang) {
            return redirect()->route('mahasiswa.magang');
        }

        return view('mahasiswa.magang.detail', [
            'lowongan' => $lowongan,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'score' => $score,
            'pengajuanMagang' => $pengajuanMagang,
            'lokasi' => $lokasi,
            'jarak' => LocationService::haversineDistance(
                $lokasi->latitude,
                $lokasi->longitude,
                $preferensiLokasi->latitude,
                $preferensiLokasi->longitude
            ),
            'days' => $diff->format('%r%a'),
            'backable' => request()->query('backable', false)
        ]);
    }

    public function ajukan($lowongan_id)
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->where('lowongan_id', $lowongan_id)->value('pengajuan_id');
        if ($pengajuanMagang) {
            abort(403, 'Anda sudah pernah mengajukan magang pada lowongan ini');
        }
        $lowongan = LowonganMagang::find($lowongan_id);
        if ($lowongan->is_active == 0) {
            abort(403, 'Lowongan ini telah ditutup');
        }
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));
        return view('mahasiswa.magang.ajukan.index', [
            'lowongan' => $lowongan,
            'user' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'keahlian_mahasiswa' => KeahlianMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('keahlian')->get(),
            'days' => $diff->format('%r%a'),
            'page' => request()->query('page'),
            'pengajuanMagang' => 'hidden'
        ]);
    }

    public function ajukanPost(Request $request, $lowongan_id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_mahasiswa' => ['nullable', 'string', 'max:255'],
            'dokumen_input.*' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'jenis_dokumen.*' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $dataLowongan = $request->only(['catatan_mahasiswa']);
            $dataLowongan['mahasiswa_id'] = Auth::user()->user_id;
            $dataLowongan['lowongan_id'] = $lowongan_id;
            $dataLowongan['status'] = 'menunggu';
            $dataLowongan['tanggal_pengajuan'] = now()->format('Y-m-d');
            $pengajuanMagang = PengajuanMagang::create($dataLowongan);

            $requiredDokumen = array_map(fn($dokumen) => trim(strtolower($dokumen)), explode(';', LowonganMagang::find($lowongan_id)->persyaratanMagang->dokumen_persyaratan));
            $dokumenInput = $request->file('dokumen_input', []);
            $jenisDokumen = $request->input('jenis_dokumen', []);

            $requiredDokumen = array_filter($requiredDokumen, fn($dokumen) => strtolower($dokumen) != 'cv');
            foreach ($jenisDokumen as $dokumen) {
                $lowerDokumen = strtolower($dokumen);
                $key = array_search(strtolower($lowerDokumen), $requiredDokumen);
                if ($key !== false) {
                    unset($requiredDokumen[$key]);
                }
            }

            if (count($requiredDokumen) != 0) {
                return response()->json([
                    'message' => 'Dokumen ' . $dokumen . ' tidak sesuai dengan persyaratan lowongan ini'
                ], 422);
            }

            foreach ($dokumenInput as $index => $dokumen) {
                if (strtolower($jenisDokumen[$index]) == 'cv') {
                    continue;
                }
                $dokumenName = 'dokumen-' . $jenisDokumen[$index] . '-pengajuan-' . $lowongan_id . '-' . Auth::user()->username . '.pdf';
                $dokumen->storeAs(DokumenPengajuan::$publicPrefixPathFile, $dokumenName);

                DokumenPengajuan::create([
                    'pengajuan_id' => $pengajuanMagang->pengajuan_id,
                    'path_file' => $dokumenName,
                    'jenis_dokumen' => $jenisDokumen[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $profilMahasiswa = ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->first();
            $cvName = 'dokumen-CV-pengajuan-' . $lowongan_id . '-' . Auth::user()->username . '.pdf';
            DokumenPengajuan::create([
                'pengajuan_id' => $pengajuanMagang->pengajuan_id,
                'path_file' => $cvName,
                'jenis_dokumen' => 'CV',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if (Storage::exists(ProfilMahasiswa::$publicPrefixFileCv . $profilMahasiswa->getRawOriginal('file_cv'))) {
                Storage::copy(ProfilMahasiswa::$publicPrefixFileCv . $profilMahasiswa->getRawOriginal('file_cv'), DokumenPengajuan::$publicPrefixPathFile  . $cvName);
            } else {
                return response()->json(['message' => 'CV tidak ditemukan'], 404);
            }

            $lowonganMagang = LowonganMagang::find($lowongan_id);
            $lowonganMagang->decrement('kuota');
            if ($lowonganMagang->kuota <= 0) {
                $lowonganMagang->update(['is_active' => 0]);
            }

            DB::commit();

            $admin = User::where('role', 'admin')->first();
            $admin->notify(new UserNotification((object)[
                'title' => 'Pengajuan Magang Baru',
                'message' => '#' . $lowongan_id . ' - ' . Auth::user()->profilMahasiswa->nama,
                'linkTitle' => 'Lihat Detail',
                'link' =>  str_replace(url('/'), '', route('admin.magang.kegiatan.detail', ['pengajuan_id' => $pengajuanMagang->pengajuan_id]))
            ]));
            return response()->json(['message' => 'Pengajuan magang berhasil dikirim.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => "Kesalahan pada server",
                'console' => $th->getMessage()
            ], 500);
        }
    }

    public function SPKDD()
    {
        dd(SPKService::getRecommendations(Auth::user()->user_id, null, true));
        dump('FINAL RESULT');
    }
}
