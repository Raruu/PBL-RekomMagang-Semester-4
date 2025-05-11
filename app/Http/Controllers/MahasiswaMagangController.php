<?php

namespace App\Http\Controllers;

use App\Models\KeahlianLowongan;
use App\Models\LowonganMagang;
use App\Models\ProfilMahasiswa;
use App\Services\SPKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaMagangController extends Controller
{
    public function magang(Request $request)
    {
        if ($request->ajax()) {
            $lowonganMagang = SPKService::getRecommendations(Auth::user()->user_id);
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
                ->addColumn('deskripsi', function ($row) {
                    return $row['lowongan']->deskripsi;
                })
                ->addColumn('batas_pendaftaran', function ($row) {
                    $diff = date_diff(
                        date_create($row['lowongan']->batas_pendaftaran),
                        date_create(date('Y-m-d'))
                    );
                    return $diff->format('%a');
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
        return view('mahasiswa.magang.index', []);
    }

    public function detail($lowongan_id)
    {
        $lowonganMagang = LowonganMagang::with(['keahlianLowongan', 'perusahaan', 'persyaratanMagang'])
            ->where('lowongan_id', $lowongan_id)
            ->first();

        // dd($lowonganMagang);

        return view('mahasiswa.magang.detail', [
            'lowongan' => $lowonganMagang,
            'tingkat_kemampuan' => KeahlianLowongan::TINGKAT_KEMAMPUAN,
        ]);
    }
}
