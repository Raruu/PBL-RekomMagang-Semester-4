<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\ProfilDosen;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminMagangController extends Controller
{
    public function kegiatan(Request $request)
    {
        $pengajuanMagang = PengajuanMagang::select('pengajuan_id', 'mahasiswa_id', 'lowongan_id', 'dosen_id', 'tanggal_pengajuan', 'status')->get();
        if ($request->ajax()) {

            return DataTables::of($pengajuanMagang)
                ->addIndexColumn()
                ->addColumn('pengajuan_id', function ($row) {
                    return $row->pengajuan_id;
                })
                ->addColumn('mahasiswa', function ($row) {
                    return $row->profilMahasiswa->nama;
                })
                ->addColumn('lowongan', function ($row) {
                    return $row->lowonganMagang->judul_lowongan;
                })
                ->addColumn('dosen', function ($row) {
                    return $row->profilDosen->nama ?? '-';
                })
                ->addColumn('tanggal_pengajuan', function ($row) {
                    return $row->tanggal_pengajuan;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->make(true);
        }
        return view('admin.magang.kegiatan.index', [
            'statuses' => PengajuanMagang::STATUS,
            'dosen' => ProfilDosen::select('nama', 'dosen_id')->get()
        ]);
    }

    public function kegiatanPost(Request $request)
    {
        $rules = [
            'pengajuan_id' => ['required', 'exists:pengajuan_magang,pengajuan_id'],
            'status' => ['required', 'string', 'in:ditolak,disetujui,selesai'],
            'dosen_id' => ['required', 'exists:profil_dosen,dosen_id'],
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
            $pengajuanMagang = PengajuanMagang::where('pengajuan_id', $request->pengajuan_id)->firstOrFail();
            $pengajuanMagang->update([
                'status' => $request->status,
                'dosen_id' => $request->dosen_id,
            ]);

            $userMahasiswa = $pengajuanMagang->profilMahasiswa->user;
            $userMahasiswa->notify(new UserNotification((object) [
                'title' => 'Pengajuan Magang ' . ucfirst($request->status),
                'message' => 'Pengajuan magang ' . $pengajuanMagang->lowonganMagang->judul_lowongan . ' telah ' . $request->status,
                'linkTitle' => 'Lihat Detail',
                'link' => str_replace(url('/'), '', route('mahasiswa.magang.pengajuan.detail', $pengajuanMagang->pengajuan_id))
            ]));

            $userDosen = $pengajuanMagang->profilDosen->user;
            $userDosen->notify(new UserNotification((object) [
                'title' => 'Penugasan Bimbingan Magang',
                'message' => 'Penugasan bimbingan magang dengan mahasiswa ' . $pengajuanMagang->profilMahasiswa->nama,
                'linkTitle' => 'Lihat Detail',
                'link' => str_replace(url('/'), '', route('dosen.mahasiswabimbingan.detail', $pengajuanMagang->pengajuan_id))
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getTraceAsString(),
            ]);
        }
    }
}
