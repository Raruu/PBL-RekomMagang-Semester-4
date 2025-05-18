<?php

namespace App\Http\Controllers;



use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilDosen;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



use Illuminate\Http\Request;
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
            ->with(['user', 'lokasi', 'ProgramStudi'])
            ->first();

        $data = [
            'user' => $user,
        ];

        if (str_contains($request->url(), '/edit')) {
            return view('dosen.profile.profile-edit', $data);
        }

        return view('dosen.profile.index', $data);
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
                    return '<a href="' . $detailUrl . '" class="btn btn-sm btn-primary">Detail</a>';
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
        return view('dosen.mahasiswabimbingan.index', compact('pengajuanMagang', 'page', 'breadcrumb'));
    }


    public function detailMahasiswaBimbingan($id)
    {
        $pengajuan = PengajuanMagang::with(['ProfilMahasiswa', 'ProfilDosen', 'LowonganMagang', 'PreferensiMahasiswa', 'Lokasi'])
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
        $pengajuan = PengajuanMagang::with(['logAktivitas', 'profilMahasiswa'])
            ->where('pengajuan_id', $id)
            ->firstOrFail();

        // dd($pengajuan);
        return view('dosen.mahasiswabimbingan.detail._logAktivitasModal', compact('pengajuan'));
    }

   
public function editProfile()
{
    $user = auth()->user();
    $profil = $user->profilDosen;
    $nama = $user->profilDosen->nama;
    if (!$profil) {
        abort(404, 'Profil dosen tidak ditemukan.');
    }

    $lokasi = Lokasi::all();
    $program = ProgramStudi::all();

    return view('dosen.profile.edit', compact('profil', 'lokasi', 'program', 'user'));
}

public function updateProfile(Request $request)
{
    $id = auth()->id(); // atau Auth::id();
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi
        $rules = [
            'email'             => ['nullable', 'email', 'unique:users,email,' . $id . ',user_id'],
            'nomor_telepon'     => ['nullable', 'string', 'max:15'],
            'alamat'            => ['nullable', 'string', 'max:255'],
            'minat_penelitian'  => ['nullable', 'string', 'max:255'],
            'foto'              => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // Email → tabel users
        $user = User::find($id);
        if ($user && $request->filled('email')) {
            $user->email = $request->email;
            $user->save();
        }

        // Nomor Telepon & Minat Penelitian → tabel profil_dosen
        $profil = ProfilDosen::where('dosen_id', $id)->first();
        if ($profil) {
            $profil->nomor_telepon = $request->nomor_telepon ?? $profil->nomor_telepon;
            $profil->minat_penelitian = $request->minat_penelitian ?? $profil->minat_penelitian;

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                // Simpan dan hapus foto lama jika perlu
                if ($profil->foto && Storage::exists($profil->foto)) {
                    Storage::delete($profil->foto);
                }

                $path = $request->file('foto')->store('foto_dosen', 'public');
                $profil->foto = $path;
            }

            $profil->save();
        }

        // Alamat → tabel lokasi
        $lokasi = Lokasi::where('user_id', $id)->first();
        if ($lokasi) {
            $lokasi->alamat = $request->alamat ?? $lokasi->alamat;
            $lokasi->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Profil dosen berhasil diperbarui.'
        ]);
    }

    return redirect('/dosen/profile')->with('success', 'Profil dosen berhasil diperbarui.');
}



  
}
