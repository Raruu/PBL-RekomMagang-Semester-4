<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\ProfilAdminModel;
use App\Models\User;
use App\Models\ProfilAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->with('profilAdmin')
            ->paginate(10);

        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:user',
            'email' => 'required|string|email|max:100|unique:user',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'is_active' => true,
            ]);

            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            // Create admin profile
            ProfilAdminModel::create([
                'admin_id' => $user->user_id,
                'nama' => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'foto_profil' => $fotoPath,
            ]);

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Akun admin berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified admin.
     */
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
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->firstOrFail();

        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('user')->ignore($admin->user_id, 'user_id')],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('user')->ignore($admin->user_id, 'user_id')],
            'password' => 'nullable|string|min:8|confirmed',
            'nama' => 'required|string|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $admin->username = $request->username;
            $admin->email = $request->email;
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }
            $admin->is_active = $request->has('is_active');
            $admin->save();

            // Update admin profile
            $profilAdmin = ProfilAdminModel::findOrFail($admin->user_id);
            $profilAdmin->nama = $request->nama;
            $profilAdmin->nomor_telepon = $request->nomor_telepon;

            // Handle file upload
            if ($request->hasFile('foto_profil')) {
                // Delete old photo if exists
                if ($profilAdmin->foto_profil) {
                    Storage::disk('public')->delete($profilAdmin->foto_profil);
                }
                $profilAdmin->foto_profil = $request->file('foto_profil')->store('profile_photos', 'public');
            }

            $profilAdmin->save();

            DB::commit();
            return redirect()->route('admin.index')->with('success', 'Akun admin berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->with('profilAdmin')
            ->firstOrFail();

        if ($admin->profilAdmin && $admin->profilAdmin->foto_profil) {
            Storage::disk('public')->delete($admin->profilAdmin->foto_profil);
        }

        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Akun admin berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $admin = User::where('role', 'admin')
            ->where('user_id', $id)
            ->firstOrFail();

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $username = $admin->username;

        return redirect()->route('admin.index')->with('success', "Akun $username berhasil $status!");
    }

}