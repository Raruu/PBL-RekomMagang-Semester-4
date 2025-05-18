<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminProfilMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProfilMahasiswa::with(['User', 'programStudi'])
                ->whereHas('User', function ($query) {
                    $query->where('role', 'mahasiswa');
                })
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('username', fn($row) => $row->user->username)
                ->addColumn('email', fn($row) => $row->user->email)
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('nim', fn($row) => $row->nim)
                ->addColumn('program_studi', fn($row) => $row->programStudi ? $row->programStudi->nama_program : '-')
                ->addColumn('semester', fn($row) => $row->semester)
                ->addColumn('status', function ($row) {
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->user->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $viewBtn = '<button type="button" class="btn btn-info btn-sm view-btn" ' .
                        'data-url="' . url('/admin/pengguna/mahasiswa/' . $row->user->user_id) . '" ' .
                        'title="Lihat Detail">' .
                        '<i class="fas fa-eye"></i></button>';

                    $editBtn = '<button type="button" class="btn btn-warning btn-sm edit-btn" ' .
                        'data-url="' . url('/admin/pengguna/mahasiswa/' . $row->user->user_id . '/edit') . '" ' .
                        'title="Edit Mahasiswa">' .
                        '<i class="fas fa-edit"></i></button>';

                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->user->is_active ? 'success' : 'secondary') . '" ' .
                        'data-user-id="' . $row->user->user_id . '" ' .
                        'data-username="' . $row->user->username . '" ' .
                        'title="' . ($row->user->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->user->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm delete-btn" ' .
                        'data-url="' . url('/admin/pengguna/mahasiswa/' . $row->user->user_id) . '" ' .
                        'data-username="' . $row->user->username . '" ' .
                        'title="Hapus Mahasiswa">' .
                        '<i class="fas fa-trash"></i></button>';

                    return '<div class="action-btn-group d-flex flex-wrap justify-content-center flex-row">' .
                        $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        // Pengaturan halaman dan breadcrumb
        $page = (object) [
            'title' => 'Manajemen Profil Mahasiswa',
        ];

        $breadcrumb = (object) [
            'title' => 'Daftar Mahasiswa',
            'list' => ['Pengguna', 'Mahasiswa'],
        ];

        return view('admin.profil_mahasiswa.index', compact('page', 'breadcrumb'));
    }
}
