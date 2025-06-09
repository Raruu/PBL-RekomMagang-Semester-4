<?php
// app/Http/Controllers/AdminProfilAdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminProfilAdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProfilAdmin::with('User')
                ->whereHas('User', function ($query) {
                    $query->where('role', 'admin');
                });

            // Terapkan filter dari parameter URL
            $filter = $request->get('filter');
            if ($filter === 'active') {
                $query->whereHas('User', fn($q) => $q->where('is_active', 1));
            } elseif ($filter === 'inactive') {
                $query->whereHas('User', fn($q) => $q->where('is_active', 0));
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('username', fn($row) => $row->user->username)
                ->addColumn('email', fn($row) => $row->user->email)
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('nomor_telepon', fn($row) => $row->nomor_telepon)
                ->addColumn('status', function ($row) {
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->user->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $viewBtn = '<button type="button" class="btn btn-info btn-sm view-btn" ' .
                        'data-url="' . url('/admin/pengguna/admin/' . $row->user->user_id) . '" ' .
                        'title="Lihat Detail">' .
                        '<i class="fas fa-eye"></i></button>';

                    $editBtn = '<button type="button" class="btn btn-warning btn-sm edit-btn" ' .
                        'data-url="' . url('/admin/pengguna/admin/' . $row->user->user_id . '/edit') . '" ' .
                        'title="Edit Admin">' .
                        '<i class="fas fa-edit"></i></button>';

                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->user->is_active ? 'success' : 'secondary') . '" ' .
                        'data-user-id="' . $row->user->user_id . '" ' .
                        'data-username="' . $row->user->username . '" ' .
                        'title="' . ($row->user->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->user->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm delete-btn" ' .
                        'data-url="' . url('/admin/pengguna/admin/' . $row->user->user_id) . '" ' .
                        'data-username="' . $row->user->username . '" ' .
                        'title="Hapus Admin">' .
                        '<i class="fas fa-trash"></i></button>';

                    return '<div class="action-btn-group d-flex flex-wrap justify-content-center flex-row">' .
                        $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        // Ambil data admin untuk tampilan awal (sebelum AJAX)
        $adminData = ProfilAdmin::with('User')
            ->where('admin_id', Auth::user()->user_id)
            ->get();

        // Pengaturan halaman dan breadcrumb
        $page = (object) [
            'title' => 'Manajemen Profil Admin',
        ];

        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Pengguna', 'Admin'],
        ];

        $search = $request->query('search');

        return view('admin.profil_admin.index', compact('adminData', 'page', 'breadcrumb', 'search'));
    }

    public function create()
    {
        return view('admin.profil_admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:user',
            'email' => 'required|string|email|max:100|unique:user',
            'password' => 'required|string|min:5|confirmed',
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

        return view('admin.profil_admin.show', compact('admin'))->render();
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->with('profilAdmin')
            ->firstOrFail();

        return view('admin.profil_admin.edit', compact('admin'))->render();
    }

    public function update(Request $request, $id)
    {
        // DEBUG: Log semua data yang diterima
        \Log::info('Update request data:', [
            'all_data' => $request->all(),
            'filled_data' => $request->only(['username', 'email', 'nama', 'nomor_telepon']),
            'has_password' => $request->has('password'),
            'filled_password' => $request->filled('password'),
            'has_is_active' => $request->has('is_active'),
            'is_active_value' => $request->input('is_active')
        ]);

        // Ambil data user yang akan diupdate
        $user = User::where('user_id', $id)
            ->where('role', 'admin')
            ->with('profilAdmin')
            ->firstOrFail();

        // Validation rules
        $rules = [
            'username' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('user')->ignore($id, 'user_id')
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('user')->ignore($id, 'user_id')
            ],
            'password' => 'nullable|string|min:5|confirmed',
            'nama' => 'nullable|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::error('Validation failed:', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
                'message' => 'Validasi data gagal'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Update user fields hanya jika ada data baru
            if ($request->filled('username') && $request->username !== $user->username) {
                $user->username = $request->username;
            }

            if ($request->filled('email') && $request->email !== $user->email) {
                $user->email = $request->email;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Handle is_active checkbox
            if ($request->has('is_active')) {
                $user->is_active = $request->input('is_active') == '1' || $request->input('is_active') === true;
            } else {
                $user->is_active = false; // Checkbox unchecked
            }

            $user->save();

            // Update profil admin
            $profil = $user->profilAdmin ?? new ProfilAdmin(['admin_id' => $id]);

            // Update nama jika ada
            if ($request->filled('nama')) {
                $profil->nama = $request->nama;
            } elseif (!$profil->exists) {
                // Jika profil belum ada dan nama kosong, error
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => ['nama' => ['Nama wajib diisi untuk admin baru']],
                    'message' => 'Nama wajib diisi'
                ], 422);
            }

            // Update nomor telepon
            if ($request->has('nomor_telepon')) {
                $profil->nomor_telepon = $request->nomor_telepon;
            }

            // Handle file upload
            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama
                if ($profil->foto_profil && Storage::disk('public')->exists($profil->foto_profil)) {
                    Storage::disk('public')->delete($profil->foto_profil);
                }
                $profil->foto_profil = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            $profil->save();

            DB::commit();

            \Log::info('Update successful:', [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'nama' => $profil->nama
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data admin berhasil diperbarui',
                'data' => [
                    'username' => $user->username,
                    'nama' => $profil->nama,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data admin: ' . $e->getMessage(),
                'system_message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $admin = User::where('role', 'admin')->where('user_id', $id)->with('profilAdmin')->firstOrFail();

            // Prevent deletion of current logged-in admin
            if ($admin->user_id == Auth::user()->user_id) {
                return response()->json(['error' => 'Anda tidak dapat menghapus akun Anda sendiri'], 422);
            }

            DB::beginTransaction();

            // Delete profile photo if exists
            if ($admin->profilAdmin && $admin->profilAdmin->foto_profil) {
                Storage::disk('public')->delete($admin->profilAdmin->foto_profil);
            }

            $admin->delete();

            DB::commit();

            return response()->json(['message' => 'Akun admin berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menghapus data, data sedang dipakai!', 'console' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $admin = User::where('role', 'admin')
                ->where('user_id', $id)
                ->firstOrFail();

            // Prevent disabling current logged-in admin
            if ($admin->user_id == Auth::user()->user_id) {
                return response()->json(['error' => 'Anda tidak dapat mengubah status akun Anda sendiri'], 422);
            }

            $admin->is_active = !$admin->is_active;
            $admin->save();

            $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json(['message' => "Akun {$admin->username} berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}
