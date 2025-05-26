<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminProfilDosenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProfilDosen::with(['user', 'lokasi', 'programStudi'])
                ->whereHas('user', fn($q) => $q->where('role', 'dosen'))
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('username', fn($row) => $row->user->username)
                ->addColumn('email', fn($row) => $row->user->email)
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('program_studi', fn($row) => $row->programStudi->nama_program ?? '-')
                ->addColumn('status', function ($row) {
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->user->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $viewBtn = '<button type="button" class="btn btn-info btn-sm view-btn" ' .
                        'data-url="' . route('admin.dosen.show', $row->user->user_id) . '" ' .
                        'title="Lihat Detail">' .
                        '<i class="fas fa-eye"></i></button>';

                    $editBtn = '<button type="button" class="btn btn-warning btn-sm edit-btn" ' .
                        'data-url="' . route('admin.dosen.edit', $row->user->user_id) . '" ' .
                        'title="Edit Dosen">' .
                        '<i class="fas fa-edit"></i></button>';

                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->user->is_active ? 'success' : 'secondary') . '" ' .
                        'data-user-id="' . $row->user->user_id . '" ' .
                        'data-nama="' . $row->nama . '" ' .
                        'title="' . ($row->user->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->user->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm delete-btn" ' .
                        'data-url="' . route('admin.dosen.destroy', $row->user->user_id) . '" ' .
                        'data-nama="' . $row->nama . '" ' .
                        'title="Hapus Dosen">' .
                        '<i class="fas fa-trash"></i></button>';

                    return '<div class="action-btn-group d-flex flex-wrap justify-content-center flex-row">' .
                        $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        $page = (object) [
            'title' => 'Manajemen Profil Dosen',
        ];

        return view('admin.profil_dosen.index', compact('page'));
    }

    public function create()
    {
        $programStudi = DB::table('program_studi')->select('program_id', 'nama_program')->get();
        return view('admin.profil_dosen.create', compact('programStudi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100|unique:user',
            'password' => 'required|string|min:5|confirmed',
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:30|unique:profil_dosen',
            'program_id' => 'required|exists:program_studi,program_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $username = $request->nip;
            $user = User::create([
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dosen',
                'is_active' => true,
            ]);

            ProfilDosen::create([
                'dosen_id' => $user->user_id,
                'lokasi_id' => $request->lokasi_id ?? 1,
                'program_id' => $request->program_id,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'minat_penelitian' => null,
                'nomor_telepon' => null,
                'foto_profil' => null,
            ]);

            DB::commit();
            return response()->json(['message' => 'Akun dosen berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dosen = User::where('role', 'dosen')
            ->where('user_id', $id)
            ->with(['profilDosen', 'profilDosen.programStudi', 'profilDosen.lokasi'])
            ->firstOrFail();

        return view('admin.profil_dosen.show', compact('dosen'))->render();
    }

    public function edit($id)
    {
        $dosen = User::where('role', 'dosen')
            ->where('user_id', $id)
            ->with(['profilDosen', 'profilDosen.programStudi'])
            ->firstOrFail();

        $programStudi = DB::table('program_studi')->select('program_id', 'nama_program')->get();

        return view('admin.profil_dosen.edit', compact('dosen', 'programStudi'))->render();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('user')->ignore($id, 'user_id')
            ],
            'password' => 'nullable|string|min:5|confirmed',
            'nama' => 'required|string|max:100',
            'program_id' => 'required|exists:program_studi,program_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
                'message' => 'Validasi data gagal'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::where('user_id', $id)
                ->where('role', 'dosen')
                ->firstOrFail();

            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $profil = ProfilDosen::firstOrNew(['dosen_id' => $id]);
            $profil->nama = $request->nama;
            $profil->program_id = $request->program_id;
            $profil->lokasi_id = $request->lokasi_id ?? 1;
            $profil->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data '. $request->nama . ' berhasil diperbarui',
                'data' => [
                    'nip' => $user->username,
                    'nama' => $request->nama
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data dosen',
                'system_message' => $e->getMessage(),
                'request_data' => $request->all() // Untuk debugging
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dosen = User::where('role', 'dosen')->where('user_id', $id)->with('profilDosen')->firstOrFail();

            if ($dosen->profilDosen && $dosen->profilDosen->foto_profil) {
                Storage::disk('public')->delete($dosen->profilDosen->foto_profil);
            }

            ProfilDosen::where('dosen_id', $id)->delete();
            $dosen->delete();

            return response()->json(['message' => 'Akun dosen berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $dosen = User::where('role', 'dosen')
                ->where('user_id', $id)
                ->with('profilDosen')
                ->firstOrFail();

            $dosen->is_active = !$dosen->is_active;
            $dosen->save();

            $status = $dosen->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $nama = $dosen->profilDosen->nama ?? 'Tidak diketahui';

            return response()->json(['message' => "Akun {$nama} berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}