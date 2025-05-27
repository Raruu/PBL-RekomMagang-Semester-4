<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;

class AdminStatistikController extends Controller
{
    public function index()
    {
        return view('admin.statistik.index');
    }

    public function getMagangVsTidak(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $countsAcc = [];
        $countMhs = [];

        for ($year = $start; $year <= $end; $year++) {
            $countsAcc[$year] = ProfilMahasiswa::where('angkatan', $year)->whereHas('pengajuanMagang', function ($query) {
                $query->whereIn('status', ['selesai', 'disetujui']);
            })->count();

            $countMhs[$year] = ProfilMahasiswa::where('angkatan', $year)->count() - $countsAcc[$year];
        }

        return response()->json([
            'acc' => $countsAcc,
            'mhs' => $countMhs
        ]);
    }
}
