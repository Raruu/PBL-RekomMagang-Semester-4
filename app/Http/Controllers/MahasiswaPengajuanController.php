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
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\LocationService;
use App\Services\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaPengajuanController extends Controller
{
    public function index(Request $request)
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
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
                    return Carbon::parse($row->tanggal_pengajuan);
                })
                ->addColumn('status_magang', function ($row) {
                    $dateStart = Carbon::parse($row->lowonganMagang->tanggal_mulai);
                    $dateEnd = Carbon::parse($row->lowonganMagang->tanggal_selesai);
                    $dateNow = Carbon::now();
                    if ($dateNow->between($dateStart, $dateEnd)) {
                        return 1;
                    } elseif ($dateNow < $dateStart) {
                        return 0;
                    } else {
                        return 2;
                    }
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
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        $pengajuanMagang = PengajuanMagang::with('lowonganMagang', 'dokumenPengajuan')
            ->where('mahasiswa_id', Auth::user()->user_id)
            ->findOrFail($pengajuan_id);
        $lokasi = $pengajuanMagang->lowonganMagang->lokasi;
        $preferensiLokasi = Auth::user()->profilMahasiswa->preferensiMahasiswa->lokasi;
        $diff = date_diff(date_create(date('Y-m-d')), date_create($pengajuanMagang->lowonganMagang->batas_pendaftaran));
        $dosen = $pengajuanMagang->profilDosen;

        $dateStart = Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_mulai);
        $dateEnd = Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_selesai);
        $dateNow = Carbon::now();
        $statusMagang = $dateNow->between($dateStart, $dateEnd) ? 1 : ($dateNow < $dateStart ? 0 : 2);

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
            'statusMagang' => $statusMagang,
            'open' => request()->query('open'),
            'backable' => request()->query('backable')
        ]);
    }

    public function pengajuanDelete($pengajuan_id)
    {
        DB::beginTransaction();
        try {
            $dokumenPengajuan = DokumenPengajuan::where('pengajuan_id', $pengajuan_id)->get();
            foreach ($dokumenPengajuan as $dokumen) {
                if (Storage::exists(DokumenPengajuan::$publicPrefixPathFile . $dokumen->getRawOriginal('path_file'))) {
                    Storage::delete(DokumenPengajuan::$publicPrefixPathFile . $dokumen->getRawOriginal('path_file'));
                }
            }
            PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->findOrFail($pengajuan_id)->delete();
            $admin = User::where('role', 'admin')->first();

            $targetMessage = str_replace(url('/'), '', route('admin.magang.kegiatan.detail', ['pengajuan_id' => $pengajuan_id]));
            $notification = $admin->unreadNotifications->first(function ($notification) use ($targetMessage) {
                return $notification->data['link'] === $targetMessage;
            });
            if ($notification) {
                $notification->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Pengajuan magang berhasil dihapus.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Kesalahan pada server", 'console' => $th->getMessage()], 500);
        }
    }

    public function uploadHasil(Request $request, $pengajuan_id)
    {
        $validator = Validator::make($request->all(), [
            'file_sertifikat' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $pengajuanMagang = PengajuanMagang::where('pengajuan_id', $pengajuan_id)->findOrFail($pengajuan_id);
            $feedback = FeedbackMahasiswa::where('pengajuan_id', $pengajuan_id)->first();
            if ($feedback != null) {
                $pengajuanMagang->status = 'selesai';
                $this->notifyMagangSelesai($pengajuan_id);
            }

            $file = $request->file('file_sertifikat');
            $fileName = 'file_sertifikat-' . Auth::user()->username . '-' . $pengajuan_id . '.pdf';
            $file->storeAs('public/dokumen/mahasiswa/sertifikat', $fileName);

            $pengajuanMagang->file_sertifikat = $fileName;
            $pengajuanMagang->save();

            DB::commit();
            return response()->json(['message' => 'File sertifikat berhasil diupload.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Kesalahan pada server", 'console' => $th->getMessage()], 500);
        }
    }

    public function logAktivitas($pengajuan_id)
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        $pengajuanMagang = PengajuanMagang::with('lowonganMagang', 'dokumenPengajuan')
            ->where('mahasiswa_id', Auth::user()->user_id)
            ->findOrFail($pengajuan_id);
        return view('mahasiswa.magang.log-aktivitas.index', [
            'pengajuan_id' => $pengajuan_id,
            'pengajuanMagang' => $pengajuanMagang
        ]);
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
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            if ($log_id == 'new') {
                LogAktivitas::create([
                    'pengajuan_id' => $request->pengajuan_id,
                    'aktivitas' => Utils::sanitizeString($request->aktivitas),
                    'kendala' => Utils::sanitizeString($request->kendala),
                    'solusi' => Utils::sanitizeString($request->solusi),
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
            return response()->json(['message' => 'Log aktivitas berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Kesalahan pada server', 'console' => $th->getMessage()], 500);
        }
    }

    public function logAktivitasExcel($pengajuan_id)
    {
        $logAktivitas = LogAktivitas::select(
            'aktivitas',
            'kendala',
            'solusi',
            'feedback_dosen',
            'tanggal_log',
            'jam_kegiatan',
        )->where('pengajuan_id', $pengajuan_id)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Jam Kegiatan');
        $sheet->setCellValue('D1', 'Aktivitas');
        $sheet->setCellValue('E1', 'Kendala');
        $sheet->setCellValue('F1', 'Solusi');
        $sheet->setCellValue('G1', 'Feedback Dosen');

        $row = 2;
        foreach ($logAktivitas as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->tanggal_log);
            $sheet->setCellValue('C' . $row, $item->jam_kegiatan);
            $sheet->setCellValue('D' . $row, $item->aktivitas);
            $sheet->setCellValue('E' . $row, $item->kendala);
            $sheet->setCellValue('F' . $row, $item->solusi);
            $sheet->setCellValue('G' . $row, $item->feedback_dosen);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'log-aktivitas-' . Auth::user()->username . '-' . date('d-m-Y H:i') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, dMY H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function feedback($pengajuan_id)
    {
        $feedback = FeedbackMahasiswa::select('kendala', 'komentar', 'pengajuan_id', 'pengalaman_belajar', 'rating', 'saran')
            ->where('pengajuan_id', $pengajuan_id)
            ->first();
        return response()->json(['data' => $feedback]);
    }

    public function feedbackPost(Request $request, $pengajuan_id)
    {
        $validator = Validator::make($request->all(), [
            'kendala' => ['required', 'string'],
            'komentar' => ['required', 'string'],
            'pengalaman_belajar' => ['required', 'string'],
            'rating' => ['required', 'int'],
            'saran' => ['required', 'string',],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $pengajuanMagang = PengajuanMagang::where('pengajuan_id', $pengajuan_id)->first();
            $fileSertif = $pengajuanMagang->file_sertifikat;
            if ($fileSertif != null) {
                $pengajuanMagang->update([
                    'status' => 'selesai'
                ]);
                $this->notifyMagangSelesai($pengajuan_id);
            }

            FeedbackMahasiswa::updateOrCreate(
                ['pengajuan_id' => $pengajuan_id],
                [
                    'kendala' => $request->kendala,
                    'komentar' => $request->komentar,
                    'pengalaman_belajar' => $request->pengalaman_belajar,
                    'rating' => $request->rating,
                    'saran' => $request->saran,
                ]
            );
            DB::commit();
            return response()->json(['message' => 'Feedback berhasil diperbarui.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Kesalahan pada server', 'console' => $th->getMessage()], 500);
        }
    }

    protected static function notifyMagangSelesai($pengajuan_id)
    {
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new UserNotification((object)[
            'title' => 'Magang Selesai',
            'message' => 'Magang ' . Auth::user()->username . ' telah selesai',
            'linkTitle' => 'Lihat Detail',
            'link' => str_replace(url('/'), '', route('admin.magang.kegiatan.detail', ['pengajuan_id' => $pengajuan_id]))
        ]));
    }
}
