<?php

namespace App\Http\Controllers;

use App\Models\DokumenPengajuan;
use App\Models\FeedbackMahasiswa;
use App\Models\FeedBackSpk;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\LogAktivitas;
use App\Models\LowonganMagang;
use App\Models\PengajuanMagang;
use App\Services\LocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->with('lowonganMagang')->get();
        if ($request->ajax()) {
            return DataTables::of($pengajuanMagang)
                ->addColumn('lowongan_id', function ($row) {
                    return $row->lowonganMagang->lowongan_id;
                })
                ->addColumn('judul', function ($row) {
                    return $row->lowonganMagang->judul_lowongan;
                })
                ->addColumn('tipe_kerja_lowongan', function ($row) {
                    return $row->lowonganMagang->tipe_kerja_lowongan;
                })
                ->addColumn('deskripsi', function ($row) {
                    return $row->lowonganMagang->deskripsi;
                })
                ->addColumn('keahlian_lowongan', function ($row) {
                    $keahlian = '';
                    foreach ($row->lowonganMagang->keahlianLowongan as $keahlianLowongan) {
                        $keahlian .= $keahlianLowongan->keahlian->nama_keahlian . ', ';
                    }
                    return rtrim($keahlian, ', ');
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('tanggal_pengajuan', function ($row) {
                    return Carbon::parse($row->tanggal_pengajuan)->format('d/m/Y');
                })
                ->make(true);
        }

        $metrik = [
            'total' => $pengajuanMagang->count(),
            'menunggu' => $pengajuanMagang->where('status', 'menunggu')->count(),
            'ditolak' => $pengajuanMagang->where('status', 'ditolak')->count(),
            'selesai' => $pengajuanMagang->where('status', 'selesai')->count(),
        ];

        return view('mahasiswa.magang.pengajuan.index', [
            'tipeKerja' => LowonganMagang::TIPE_KERJA,
            'keahlian' => Keahlian::all(),
            'metrik' => $metrik,
            'status' => PengajuanMagang::STATUS
        ]);
    }

    public function pengajuanDetail($pengajuan_id)
    {
        $pengajuanMagang = PengajuanMagang::with('lowonganMagang', 'dokumenPengajuan')
            ->where('mahasiswa_id', Auth::user()->user_id)
            ->findOrFail($pengajuan_id);
        $lokasi = $pengajuanMagang->lowonganMagang->lokasi;
        $preferensiLokasi = Auth::user()->profilMahasiswa->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($pengajuanMagang->lowonganMagang->batas_pendaftaran));
        $dosen = $pengajuanMagang->profilDosen;

        return view('mahasiswa.magang.pengajuan.detail', [
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'pengajuanMagang' => $pengajuanMagang,
            'lokasi' => $lokasi,
            'jarak' => LocationService::haversineDistance(
                $lokasi->latitude,
                $lokasi->longitude,
                $preferensiLokasi->latitude,
                $preferensiLokasi->longitude
            ),
            'dosen' => $dosen,
            'days' => $diff->format('%r%a'),
        ]);
    }

    public function pengajuanDelete($pengajuan_id)
    {
        DB::beginTransaction();
        try {
            PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->findOrFail($pengajuan_id)->delete();
            DokumenPengajuan::where('pengajuan_id', $pengajuan_id)->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Pengajuan magang berhasil dihapus.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function logAktivitas($pengajuan_id)
    {
        return view('mahasiswa.magang.log-aktivitas.index', ['pengajuan_id' => $pengajuan_id]);
    }

    public function logAktivitasData(Request $request, $pengajuan_id)
    {
        if ($request->wantsJson()) {
            $logAktivitas = LogAktivitas::select(
                'tanggal_log',
                'aktivitas',
                'kendala',
                'solusi',
                'jam_kegiatan',
                'feedback_dosen',
                'log_id',
            )->where('pengajuan_id', $pengajuan_id)->get();
            $logAktivitas = $logAktivitas->groupBy('tanggal_log')->map(function ($items) {
                return $items->map(function ($item) {
                    return (object)[
                        'aktivitas' => $item->aktivitas ?? '-',
                        'kendala' => $item->kendala ?? '-',
                        'solusi' => $item->solusi ?? '-',
                        'jam_kegiatan' => $item->jam_kegiatan ?? '-',
                        'feedback_dosen' => $item->feedback_dosen ?? '-',
                        'log_id' => $item->log_id,
                    ];
                });
            });
            return response()->json(['data' => $logAktivitas]);
        }
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

    public function feedbackPost($pengajuan_id)
    {
        $validator = Validator::make(request()->all(), [
            'kendala' => ['required', 'string'],
            'komentar' => ['required', 'string'],
            'pengalaman_belajar' => ['required', 'string'],
            'rating' => ['required', 'int'],
            'saran' => ['required', 'string',],
        ]);

        if (PengajuanMagang::where('pengajuan_id', $pengajuan_id)->first()->status != 'selesai') {
            return response()->json([
                'message' => 'Magang belum selesai'
            ], 422);
        }

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
