<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
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
                    return $row->profilDosen->nama;
                })
                ->addColumn('tanggal_pengajuan', function ($row) {
                    return $row->tanggal_pengajuan;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->make(true);
        }
        return view('admin.magang.kegiatan.index');
    }
}
