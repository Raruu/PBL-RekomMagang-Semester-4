<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProfilAdmin::with('User')
                ->where('admin_id', Auth::user()->user_id)
                ->get();
            return DataTables::of($data)
                ->addColumn('username', function ($row) {
                    return $row->user->username;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('nomor_telepon', function ($row) {
                    return $row->nomor_telepon;
                })
                ->addColumn('foto_profil', function ($row) {
                    return '<img src="' . asset('storage/' . $row->foto_profil) . '" alt="Foto Profil" width="50" height="50">';
                })
                ->addColumn('status', function ($row) {
                    $label = $row->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $statusBtn = '<form action="' . route('admin.toggle-status', $row->user->user_id) . '" method="POST" class="d-inline">' .
                        csrf_field() .
                        method_field('PATCH') .
                        '<button type="submit" class="btn btn-sm btn-' . ($row->is_active ? 'secondary' : 'success') . '" title="' . ($row->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->is_active ? 'toggle-off' : 'toggle-on') . '"></i>' .
                        '</button></form>';

                    $buttons = '<div class="btn-group" role="group">
                    <a href="' . route('admin.show', $row->user->user_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="' . route('admin.edit', $row->user->user_id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                    ' . $statusBtn . '
                    <form action="' . route('admin.destroy', $row->user->user_id) . '" method="POST" class="d-inline delete-form">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>';
                    return $buttons;
                })
                ->rawColumns(['foto_profil', 'status', 'aksi'])
                ->make(true);
        }

        // Ambil data admin untuk tampilan awal (sebelum AJAX)
        $adminData = ProfilAdmin::with('User')
            ->where('admin_id', Auth::user()->user_id)
            ->get();

        // Pengaturan halaman dan breadcrumb
        $page = (object) [
            'title' => 'Manajemen Admin',
        ];

        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Dashboard', 'Admin'],
        ];

        return view('admin.index', compact('adminData', 'page', 'breadcrumb'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:user',
            'email' => 'required|string|email|max:100|unique:user',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
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
                'role' => 'admin',
                'is_active' => true,
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            ProfilAdmin::create([
                'admin_id' => $user->user_id,
                'nama' => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'foto_profil' => $fotoPath,
            ]);

            DB::commit();
            return response()->json(['message' => 'Akun admin berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->with('profilAdmin')
            ->firstOrFail();

        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit($id)
    {
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->with('profilAdmin')
            ->firstOrFail();

        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->where('user_id', $id)->firstOrFail();

        $validator = \Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:50', Rule::unique('user')->ignore($admin->user_id, 'user_id')],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('user')->ignore($admin->user_id, 'user_id')],
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $admin->username = $request->username;
            $admin->email = $request->email;
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();

            $profil = ProfilAdmin::findOrFail($admin->user_id);
            $profil->nama = $request->nama;
            $profil->nomor_telepon = $request->nomor_telepon;

            if ($request->hasFile('foto_profil')) {
                if ($profil->foto_profil) {
                    Storage::disk('public')->delete($profil->foto_profil);
                }
                $profil->foto_profil = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            $profil->save();

            DB::commit();
            return response()->json(['message' => 'Akun admin berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $admin = User::where('role', 'admin')->where('user_id', $id)->with('profilAdmin')->firstOrFail();

            if ($admin->profilAdmin && $admin->profilAdmin->foto_profil) {
                Storage::disk('public')->delete($admin->profilAdmin->foto_profil);
            }

            $admin->delete();

            return response()->json(['message' => 'Akun admin berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $admin = User::where('role', 'admin')
                ->where('user_id', $id)
                ->firstOrFail();

            $admin->is_active = !$admin->is_active;
            $admin->save();

            $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json(['message' => "Akun {$admin->username} berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}