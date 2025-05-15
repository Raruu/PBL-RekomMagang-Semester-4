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
                ->addColumn('nip', fn($row) => $row->nip)
                ->addColumn('program_studi', fn($row) => $row->programStudi->nama_program ?? '-')
                ->addColumn('status', function ($row) {
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->user->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->user->is_active ? 'success' : 'secondary') . '" ' .
                        'data-user-id="' . $row->user->user_id . '" ' .
                        'data-nama="' . $row->nama . '" ' .
                        'title="' . ($row->user->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->user->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    return '<div class="btn-group" role="group">
                    <a href="' . url('/admin/pengguna/dosen/' . $row->user->user_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="' . url('/admin/pengguna/dosen/' . $row->user->user_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    ' . $statusBtn . '
                    <form action="' . url('/admin/pengguna/dosen/' . $row->user->user_id) . '" method="POST" class="d-inline delete-form">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        $page = (object) [
            'title' => 'Manajemen Profil Dosen',
        ];

        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list' => ['Pengguna', 'Dosen'],
        ];

        return view('admin.profil_dosen.index', compact('page', 'breadcrumb'));
    }

    public function create()
    {
        return view('admin.profil_dosen.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:user',
            'email' => 'required|email|max:100|unique:user',
            'password' => 'required|string|min:5|confirmed',
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:30',
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'program_id' => 'required|exists:program_studi,program_id',
            'minat_penelitian' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'dosen',
                'is_active' => true,
            ]);

            $fotoPath = $request->hasFile('foto_profil')
                ? $request->file('foto_profil')->store('profile_photos', 'public')
                : null;

            ProfilDosen::create([
                'dosen_id' => $user->user_id,
                'lokasi_id' => $request->lokasi_id,
                'program_id' => $request->program_id,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'minat_penelitian' => $request->minat_penelitian,
                'nomor_telepon' => $request->nomor_telepon,
                'foto_profil' => $fotoPath,
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
        $dosen = User::where('role', 'dosen')->where('user_id', $id)->with('profilDosen')->firstOrFail();
        return view('admin.profil_dosen.show', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = User::where('role', 'dosen')->where('user_id', $id)->with('profilDosen')->firstOrFail();
        return view('admin.profil_dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = User::where('role', 'dosen')->where('user_id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:50', Rule::unique('user')->ignore($dosen->user_id, 'user_id')],
            'email' => ['required', 'email', 'max:100', Rule::unique('user')->ignore($dosen->user_id, 'user_id')],
            'password' => 'nullable|string|min:5|confirmed',
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:30',
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'program_id' => 'required|exists:program_studi,program_id',
            'minat_penelitian' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $dosen->username = $request->username;
            $dosen->email = $request->email;
            if ($request->filled('password')) {
                $dosen->password = Hash::make($request->password);
            }
            $dosen->save();

            $profil = ProfilDosen::findOrFail($dosen->user_id);
            $profil->nama = $request->nama;
            $profil->nip = $request->nip;
            $profil->lokasi_id = $request->lokasi_id;
            $profil->program_id = $request->program_id;
            $profil->minat_penelitian = $request->minat_penelitian;
            $profil->nomor_telepon = $request->nomor_telepon;

            if ($request->hasFile('foto_profil')) {
                if ($profil->foto_profil) {
                    Storage::disk('public')->delete($profil->foto_profil);
                }
                $profil->foto_profil = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            $profil->save();

            DB::commit();
            return response()->json(['message' => 'Akun dosen berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dosen = User::where('role', 'dosen')->where('user_id', $id)->with('profilDosen')->firstOrFail();

            if ($dosen->profilDosen && $dosen->profilDosen->foto_profil) {
                Storage::disk('public')->delete($dosen->profilDosen->foto_profil);
            }

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
