<?php

namespace App\Http\Controllers;

use App\Models\DokumenPengajuan;
use App\Models\FeedbackMahasiswa;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\LogAktivitas;
use App\Models\LowonganMagang;
use App\Models\PengajuanMagang;
use App\Models\ProfilMahasiswa;
use App\Services\LocationService;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaMagangController extends Controller
{
    public function index(Request $request)
    {
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
                ->make(true);
        }
        return view('mahasiswa.magang.index', [
            'keahlian' => Keahlian::all(),
            'tipeKerja' => LowonganMagang::TIPE_KERJA,
            'mahasiswa' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
        ]);
    }

    public function magangDetail($lowongan_id)
    {
        $lowonganMagang = collect(session('lowonganMagang') ?: SPKService::getRecommendations(Auth::user()->user_id));
        $lowonganMagang = $lowonganMagang->firstWhere('lowongan.lowongan_id', $lowongan_id);
        $lowongan = $lowonganMagang['lowongan'];
        $score = $lowonganMagang['score'];
        $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->where('lowongan_id', $lowongan_id)->value('pengajuan_id');
        $lokasi = $lowongan->lokasi;
        $preferensiLokasi = Auth::user()->profilMahasiswa->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));

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
        $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->where('lowongan_id', $lowongan_id)->value('pengajuan_id');
        if ($pengajuanMagang) {
            abort(403, 'Anda sudah pernah mengajukan magang pada lowongan ini');
        }
        $lowongan = LowonganMagang::find($lowongan_id);
        $diff = date_diff(date_create(date('Y-m-d')), date_create($lowongan->batas_pendaftaran));
        return view('mahasiswa.magang.ajukan.index', [
            'lowongan' => $lowongan,
            'user' => ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('preferensiMahasiswa')->first(),
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'keahlian_mahasiswa' => KeahlianMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->with('keahlian')->get(),
            'days' => $diff->format('%r%a'),
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
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        DB::beginTransaction();
        try {
            $dataLowongan = $request->only(['catatan_mahasiswa']);
            $dataLowongan['mahasiswa_id'] = Auth::user()->user_id;
            $dataLowongan['lowongan_id'] = $lowongan_id;
            $dataLowongan['status'] = 'menunggu';
            $dataLowongan['tanggal_pengajuan'] = now()->format('Y-m-d');
            $pengajuanMagang = PengajuanMagang::create($dataLowongan);

            $dokumenInput = $request->file('dokumen_input', []);
            $jenisDokumen = $request->input('jenis_dokumen', []);
            foreach ($dokumenInput as $index => $dokumen) {
                $dokumenName = 'dokumen-' . $jenisDokumen[$index] . '-pengajuan-' . $lowongan_id . '-' . Auth::user()->username . '.pdf';
                $dokumen->storeAs('public/dokumen/mahasiswa/', $dokumenName);

                DokumenPengajuan::create([
                    'pengajuan_id' => $pengajuanMagang->pengajuan_id,
                    'path_file' => $dokumenName,
                    'jenis_dokumen' => $jenisDokumen[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Pengajuan magang berhasil dikirim.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th]);
        }
    }

    public function logAktivitas($pengajuan_id)
    {
        $logAktivitas = LogAktivitas::select(
            'tanggal_log',
            'aktivitas',
            'kendala',
            'solusi',
            'jam_kegiatan',
            'feedback_dosen',
            'log_id',
        )
            ->where('pengajuan_id', $pengajuan_id)->get();
        $logAktivitas = $logAktivitas->groupBy('tanggal_log')->map(function ($items) {
            return $items->map(function ($item) {
                return (object)[
                    'aktivitas' => $item->aktivitas,
                    'kendala' => $item->kendala,
                    'solusi' => $item->solusi,
                    'jam_kegiatan' => $item->jam_kegiatan,
                    'feedback_dosen' => $item->feedback_dosen,
                    'log_id' => $item->log_id,
                ];
            });
        });
        return view('mahasiswa.magang.log-aktivitas.index', ['logAktivitas' => $logAktivitas, 'pengajuan_id' => $pengajuan_id]);
    }

    public function logAktivitasUpdate(Request $request, $log_id)
    {
        $validator = Validator::make($request->all(), [
            'aktivitas' => ['required', 'string', 'max:255'],
            'kendala' => ['nullable', 'string', 'max:255'],
            'solusi' => ['nullable', 'string', 'max:255'],
            'jam_kegiatan' => ['nullable', 'date_format:H:i:s', 'max:255'],
            'tanggal_log' => ['required', 'date_format:Y-m-d', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'msgField' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            if ($log_id == 'new') {
                LogAktivitas::create([
                    'pengajuan_id' => $request->pengajuan_id,
                    'aktivitas' => $request->aktivitas,
                    'kendala' => $request->kendala,
                    'solusi' => $request->solusi,
                    'jam_kegiatan' => $request->jam_kegiatan,
                    'tanggal_log' => $request->tanggal_log,
                ]);
            } else {
                LogAktivitas::where('log_id', $log_id)->update([
                    'aktivitas' => $request->aktivitas,
                    'kendala' => $request->kendala,
                    'solusi' => $request->solusi,
                    'jam_kegiatan' => $request->jam_kegiatan,
                    'tanggal_log' => $request->tanggal_log,
                ]);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Log aktivitas berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function feedback($pengajuan_id)
    {
        $feedback = FeedbackMahasiswa::select('kendala', 'komentar', 'pengajuan_id', 'pengalaman_belajar', 'rating', 'saran')
            ->where('pengajuan_id', $pengajuan_id)
            ->first();
        return response()->json(['data' => $feedback]);
    }

    public function feedbackPost($pengajuan_id){
        $validator = Validator::make(request()->all(), [
            'kendala' => ['required', 'string'],
            'komentar' => ['required', 'string'],
            'pengalaman_belajar' => ['required', 'string'],
            'rating' => ['required', 'int'],
            'saran' => ['required', 'string', ],
        ]);

        if ($validator->fails()) {
            return response()->json([    
                'message' => $validator->errors()->first(),
                'msgField' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            FeedbackMahasiswa::where('pengajuan_id', $pengajuan_id)->update([
                'kendala' => request()->kendala,
                'komentar' => request()->komentar,
                'pengalaman_belajar' => request()->pengalaman_belajar,
                'rating' => request()->rating,
                'saran' => request()->saran,
            ]);
            DB::commit();
            return response()->json(['message' => 'Feedback berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
