<?php

namespace App\Http\Controllers;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilDosen;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\ProfilMahasiswa;


use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Mahasiswa Bimbingan',
            'list' => ['Home', 'Mahasiswa Bimbingan']
        ];
    
        $page = (object) [
            'title' => 'Daftar Mahasiswa Bimbingan'
        ];
    
        $activeMenu = 'bimbingan';
    
        $user = Auth::user();
        $profilDosen = ProfilDosen::where('user_id', $user->id)->first();
    
        if ($request->ajax()) {
            // Ambil mahasiswa bimbingan dan eager load profil + program
            $mahasiswa = $profilDosen->mahasiswaBimbingan()
                            ->with(['profil', 'program']);
    
            return DataTables::of($mahasiswa)
                ->addIndexColumn()
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->profil->nama_lengkap ?? '-';
                })
                ->addColumn('nim', function ($row) {
                    return $row->profil->nim ?? '-';
                })
                ->addColumn('program_studi', function ($row) {
                    return $row->program->nama_program ?? '-';
                })
                ->addColumn('semester', function ($row) {
                    return $row->profil->semester ?? '-';
                })
                ->addColumn('telepon', function ($row) {
                    return $row->profil->nomor_telefon ?? '-';
                })
                ->addColumn('alamat', function ($row) {
                    return $row->profil->alamat ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    return '<a href="' . route('mahasiswa.show', $row->id) . '" class="btn btn-sm btn-info">Detail</a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    
        return view('dosen.mahasiswa_bimbingan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
