<?php

namespace App\Http\Controllers;

use App\Models\DokumenPengajuan;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\LowonganMagang;
use App\Models\PengajuanMagang;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaPengajuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pengajuanMagang = PengajuanMagang::where('mahasiswa_id', Auth::user()->user_id)->with('lowonganMagang')->get();
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
                ->make(true);
        }
        return view('mahasiswa.magang.pengajuan.index', [
            'tipeKerja' => LowonganMagang::TIPE_KERJA,
            'keahlian' => Keahlian::all(),
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
        }
    }
}
