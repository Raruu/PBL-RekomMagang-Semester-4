<?php

namespace App\Http\Controllers;


use App\Models\PengajuanMagang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilDosen;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Profiler\Profile;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dosen.dashboard');
    }

    public function profile(Request $request)
    {
        $user = ProfilDosen::where('dosen_id', Auth::user()->user_id)
            ->with(['user', 'lokasi', 'ProgramStudi']) // pastikan relasi ini ada di model
            ->first();

        $data = [
            'user' => $user,
        ];

        if (str_contains($request->url(), '/edit')) {
            return view('dosen.profile.profile-edit', $data);
        }

        return view('dosen.profile.profile', $data);
    }


    public function tampilMahasiswaBimbingan(Request $request)
    {
        if ($request->ajax()) {
            // Ambil data pengajuan magang dengan relasi mahasiswa dan dosen
            $data = PengajuanMagang::with(['ProfilMahasiswa', 'ProfilDosen'])
                ->where('dosen_id', Auth::user()->user_id)
                ->get();

            // Return data ke DataTables
            return DataTables::of($data)
                ->addColumn('nama_mahasiswa', function ($row) {
                    return $row->mahasiswa->nama ?? '-';
                })
                ->addColumn('lowongan_id', function ($row) {
                    return $row->lowongan_id;
                })
                ->addColumn('nama_dosen', function ($row) {
                    return $row->dosen->nama ?? '-';
                })
                ->addColumn('tanggal_pengajuan', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal_pengajuan));
                })
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status); // tampilkan status seperti "Diterima", "Ditolak", dll.
                })
                ->addColumn('action', function ($row) {
                    $detailUrl = route('dosen.mahasiswabimbingan.detail', $row->id);
                    return '<a href="'.$detailUrl.'" class="btn btn-sm btn-primary">Detail</a>';
                })
                
                ->make(true);
        }

        // Ambil data pengajuan magang untuk tampilan awal (sebelum AJAX)
        $pengajuanMagang = PengajuanMagang::with(['ProfilMahasiswa', 'ProfilDosen'])
            ->where('dosen_id', Auth::user()->user_id)
            ->get();
        //dd($pengajuanMagang);
        // Pengaturan halaman dan breadcrumb
        $page = (object)[
            'title' => 'Mahasiswa Bimbingan Magang',
        ];

        $breadcrumb = (object)[
            'title' => 'Mahasiswa Bimbingan',
            'list' => ['Dashboard', 'Mahasiswa Bimbingan'],
        ];

        // Kirimkan variabel pengajuanMagang ke view
        return view('dosen.mahasiswabimbingan.mahasiswabimbingan', compact('pengajuanMagang', 'page', 'breadcrumb'));
    }

    public function detailMahasiswaBimbingan($id)
    {
        $pengajuan = PengajuanMagang::with(['ProfilMahasiswa', 'ProfilDosen', 'LowonganMagang', 'PreferensiMahasiswa','Lokasi'])
            ->findOrFail($id);

        $page = (object)[
            'title' => 'Detail Mahasiswa Bimbingan',
        ];

        $breadcrumb = (object)[
            'title' => 'Detail Mahasiswa Bimbingan',
            'list' => ['Dashboard', 'Mahasiswa Bimbingan', 'Detail'],
        ];

        return view('dosen.mahasiswabimbingan.detail', compact('pengajuan', 'page', 'breadcrumb'));
    }

    public function logAktivitas($id)
    {
       // $logAktifitas = 
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
