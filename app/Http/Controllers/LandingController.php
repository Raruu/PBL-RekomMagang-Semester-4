<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $perusahaan = PerusahaanMitra::with(['lokasi', 'lowonganMagang', 'bidangIndustri'])
            ->where('is_active', 1)
            ->get()
            ->chunk(3);

        return view('landing', ['perusahaanChunk' => $perusahaan]);
    }
}
