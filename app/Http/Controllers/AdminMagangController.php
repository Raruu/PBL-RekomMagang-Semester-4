<?php

namespace App\Http\Controllers;

use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\PengajuanMagang;
use App\Models\ProfilDosen;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
                    return Carbon::parse($row->tanggal_pengajuan)->format('d/m/Y');
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

    public function kegiatanDetail($pengajuan_id)
    {
        $pengajuan = PengajuanMagang::where('pengajuan_id', $pengajuan_id)->firstOrFail();
        $lokasi = $pengajuan->lowonganMagang->lokasi;
        return view('admin.magang.kegiatan.detail', [
            'pengajuan_id' => $pengajuan_id,
            'pengajuanMagang' => $pengajuan,
            'lowongan' => $pengajuan->lowonganMagang,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
            'keahlian_mahasiswa' => $pengajuan->profilMahasiswa->keahlianMahasiswa,
            'statuses' => PengajuanMagang::STATUS,
            'dosen' => ProfilDosen::select('nama', 'dosen_id')->get(),
            'lokasi' => $lokasi,
        ]);
    }

    public function getDosenData(Request $request)
    {
        $dosen = ProfilDosen::where('dosen_id', $request->dosen_id)->with(['user', 'programStudi'])->firstOrFail();
        return response()->json([
            'data' => $dosen
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
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ], 422);
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
                'message' => 'Data berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function uploadKeterangan(Request $request)
    {
        $rules = [
            'pengajuan_id' => ['required'],
            'keterangan_magang' => ['required', 'file', 'max:2048'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ], 422);
        }

        try {
            $pengajuanMagang = PengajuanMagang::where('pengajuan_id', $request->pengajuan_id)->firstOrFail();
            $name = 'keterangan-magang-' . $pengajuanMagang->profilMahasiswa->nim . '.pdf';
            $request->file('keterangan_magang')->storeAs('public/dokumen/mahasiswa', $name);
            $pengajuanMagang->update([
                'file_sertifikat' => $name,
            ]);
            return response()->json([
                'message' => 'Keterangan magang berhasil diupload'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function deleteKeterangan(Request $request)
    {
        $rules = [
            'pengajuan_id' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ], 422);
        }

        try {
            $pengajuanMagang = PengajuanMagang::where('pengajuan_id', $request->pengajuan_id)->firstOrFail();
            if (Storage::exists('public/dokumen/mahasiswa/' . $pengajuanMagang->file_sertifikat)) {
                Storage::delete('public/dokumen/mahasiswa/' . $pengajuanMagang->file_sertifikat);
            }
            $pengajuanMagang->update([
                'file_sertifikat' => null,
            ]);
            return response()->json([
                'message' => 'Keterangan magang berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
