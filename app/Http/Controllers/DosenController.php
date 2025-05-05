<?php

namespace App\Http\Controllers;


use App\Models\PengajuanMagang;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilDosen;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;


use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dosen.dashboard');
    }

    public function tampilMahasiswaBimbingan(Request $request)
    {
        $dosenId = auth()->user()->dosen_id;

        if ($request->ajax()) {
            // Ambil data pengajuan magang dengan relasi mahasiswa dan dosen
            $data = PengajuanMagang::with(['mahasiswa', 'dosen'])
                ->where('dosen_id', $dosenId)
                ->get();

            // Return data ke DataTables
            return DataTables::of($data)
                ->addColumn('nama_mahasiswa', function ($row) {
                    return $row->mahasiswa->nama_lengkap ?? '-';
                })
                ->addColumn('lowongan_id', function ($row) {
                    return $row->lowongan_id;
                })
                ->addColumn('nama_dosen', function ($row) {
                    return $row->dosen->nama_lengkap ?? '-';
                })
                ->addColumn('tanggal_pengajuan', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal_pengajuan));
                })
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status); // tampilkan status seperti "Diterima", "Ditolak", dll.
                })
                ->make(true);
        }

        // Ambil data pengajuan magang untuk tampilan awal (sebelum AJAX)
        $pengajuanMagang = PengajuanMagang::with(['mahasiswa', 'dosen'])
            ->where('dosen_id', $dosenId)
            ->get();

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
