<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminProfilMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProfilMahasiswa::with(['user', 'programStudi'])
                ->whereHas('user', fn($q) => $q->where('role', 'mahasiswa'))
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nim', fn($row) => $row->nim)
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('email', fn($row) => $row->user->email)
                ->addColumn('program_studi', fn($row) => $row->programStudi->nama_program ?? '-')
                ->addColumn('angkatan', fn($row) => $row->angkatan ?? '-')
                ->addColumn('status', function ($row) {
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->user->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $viewBtn = '<button type="button" class="btn btn-info btn-sm view-btn" ' .
                        'data-url="' . route('admin.mahasiswa.show', $row->user->user_id) . '" ' .
                        'title="Lihat Detail">' .
                        '<i class="fas fa-eye"></i></button>';

                    $editBtn = '<button type="button" class="btn btn-warning btn-sm edit-btn" ' .
                        'data-url="' . route('admin.mahasiswa.edit', $row->user->user_id) . '" ' .
                        'title="Edit Mahasiswa">' .
                        '<i class="fas fa-edit"></i></button>';

                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->user->is_active ? 'success' : 'secondary') . '" ' .
                        'data-user-id="' . $row->user->user_id . '" ' .
                        'data-nama="' . $row->nama . '" ' .
                        'title="' . ($row->user->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->user->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm delete-btn" ' .
                        'data-url="' . route('admin.mahasiswa.destroy', $row->user->user_id) . '" ' .
                        'data-nama="' . $row->nama . '" ' .
                        'title="Hapus Mahasiswa">' .
                        '<i class="fas fa-trash"></i></button>';

                    return '<div class="action-btn-group d-flex flex-wrap justify-content-center flex-row">' .
                        $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        $page = (object) [
            'title' => 'Manajemen Profil Mahasiswa',
        ];

        return view('admin.profil_mahasiswa.index', compact('page', 'breadcrumb'));
    }

    public function show($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')
            ->where('user_id', $id)
            ->with([
                'profilMahasiswa',
                'profilMahasiswa.lokasi',
                'profilMahasiswa.programStudi',
                'profilMahasiswa.preferensiMahasiswa',
                'profilMahasiswa.pengalamanMahasiswa',
                'profilMahasiswa.keahlianMahasiswa.keahlian'
            ])
            ->firstOrFail();

        return view('admin.profil_mahasiswa.show', compact('mahasiswa'))->render();
    }

    public function edit($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')
            ->where('user_id', $id)
            ->with(['profilMahasiswa', 'profilMahasiswa.programStudi'])
            ->firstOrFail();

        $programStudi = DB::table('program_studi')->select('program_id', 'nama_program')->get();

        return view('admin.profil_mahasiswa.edit', compact('mahasiswa', 'programStudi'))->render();
    }

    public function update(Request $request, $id)
    {
        // Validasi sebelum transaction
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('user')->ignore($id, 'user_id'),
                Rule::unique('profil_mahasiswa', 'nim')->ignore($id, 'mahasiswa_id')
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('user')->ignore($id, 'user_id')
            ],
            'password' => 'nullable|string|min:5|confirmed',
            'nama' => 'required|string|max:100',
            'program_id' => 'required|exists:program_studi,program_id',
            'angkatan' => 'nullable|integer|digits:4',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
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
            // 1. Cek apakah user ada dan role mahasiswa
            $user = User::where('user_id', $id)
                ->where('role', 'mahasiswa')
                ->firstOrFail();

            // 2. Cek apakah NIM baru sudah digunakan oleh user lain
            $existingUser = User::where('username', $request->username)
                ->where('user_id', '!=', $id)
                ->exists();

            $existingProfil = ProfilMahasiswa::where('nim', $request->username)
                ->where('mahasiswa_id', '!=', $id)
                ->exists();

            if ($existingUser || $existingProfil) {
                throw new \Exception('NIM sudah digunakan oleh mahasiswa lain');
            }

            // 3. Update data user
            $user->username = $request->username;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // 4. Update profil mahasiswa
            $profil = ProfilMahasiswa::firstOrNew(['mahasiswa_id' => $id]);
            $profil->nama = $request->nama;
            $profil->nim = $request->username; // Pastikan NIM = username
            $profil->program_id = $request->program_id;
            $profil->angkatan = $request->angkatan;
            $profil->nomor_telepon = $request->nomor_telepon;
            $profil->alamat = $request->alamat;
            $profil->lokasi_id = $request->lokasi_id ?? 1;
            $profil->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data mahasiswa berhasil diperbarui',
                'data' => [
                    'nim' => $request->username,
                    'nama' => $request->nama
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data mahasiswa',
                'system_message' => $e->getMessage(),
                'request_data' => $request->all()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mahasiswa = User::where('role', 'mahasiswa')->where('user_id', $id)->with('profilMahasiswa')->firstOrFail();

            if ($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->foto_profil) {
                Storage::disk('public')->delete($mahasiswa->profilMahasiswa->foto_profil);
            }

            if ($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->file_cv) {
                Storage::disk('public')->delete($mahasiswa->profilMahasiswa->file_cv);
            }

            ProfilMahasiswa::where('mahasiswa_id', $id)->delete();
            $mahasiswa->delete();

            return response()->json(['message' => 'Akun mahasiswa berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $mahasiswa = User::where('role', 'mahasiswa')
                ->where('user_id', $id)
                ->with('profilMahasiswa')
                ->firstOrFail();

            $mahasiswa->is_active = !$mahasiswa->is_active;
            $mahasiswa->save();

            $status = $mahasiswa->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $nama = $mahasiswa->profilMahasiswa->nama ?? 'Tidak diketahui';

            return response()->json(['message' => "Akun {$nama} berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}