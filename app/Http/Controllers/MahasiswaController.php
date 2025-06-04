<?php

namespace App\Http\Controllers;

use App\Models\FeedBackSpk;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index()
    {
        $profilMahasiswa = ProfilMahasiswa::where('mahasiswa_id', Auth::user()->user_id)->first();
        $pengajuanMagang = $profilMahasiswa->pengajuanMagang;
        $metrikPengajuan = [
            'total' => $pengajuanMagang->count(),
            'menunggu' => $pengajuanMagang->where('status', 'menunggu')->count(),
            'ditolak' => $pengajuanMagang->where('status', 'ditolak')->count(),
            'selesai' => $pengajuanMagang->where('status', 'selesai')->count(),
        ];

        return view('mahasiswa.index', [
            'user' => $profilMahasiswa,
            'metrikPengajuan' => $metrikPengajuan,
            'kegiatanMagang' => $pengajuanMagang->where('status', 'disetujui'),
        ]);
    }

    public function feedbackSPK()
    {
        if (MahasiswaAkunProfilController::checkCompletedSetup() == 0) {
            abort(403, 'Lengkapi profil terlebih dahulu');
        }
        $feedback = FeedBackSpk::where('mahasiswa_id', Auth::user()->user_id)
            ->first();
        return view('mahasiswa.evaluasi.spk.index', ['data' => $feedback]);
    }

    public function setFeedbackSPK(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'komentar' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'msgField' =>  $validator->errors()], 422);
        }

        FeedBackSpk::updateOrCreate(
            ['mahasiswa_id' => Auth::user()->user_id],
            [
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );
        return response()->json(['message' => 'Feedback berhasil disimpan']);
    }
}
