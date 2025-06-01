<?php

namespace App\Http\Controllers;

use App\Models\KeahlianLowongan;
use App\Models\LogAktivitas;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilDosen;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\LocationService;
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

        return view('dosen.profile.index', $data);
    }


  public function tampilMahasiswaBimbingan(Request $request)
{
    if ($request->ajax()) {
        // Ambil data pengajuan magang dengan relasi mahasiswa dan dosen
        $data = PengajuanMagang::with(['profilMahasiswa', 'profilDosen', 'lowonganMagang'])
            ->where('dosen_id', Auth::user()->user_id)
            ->get();
        
        // Return data ke DataTables
        return DataTables::of($data)
            ->addIndexColumn() // menambahkan kolom index
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->profilMahasiswa->nama ?? '-';
            })
            ->addColumn('lowongan', function ($row) {
                return $row->lowonganMagang->judul_lowongan ?? '-';
            })
            ->addColumn('nama_dosen', function ($row) {
                return $row->profilDosen->nama ?? '-';
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal_pengajuan));
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->status);
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('dosen.mahasiswabimbingan.detail', $row->pengajuan_id);
                return '<a href="' . $detailUrl . '" class="btn btn-sm btn-primary">Detail</a>';
            })
            ->rawColumns(['action']) // penting agar tombol HTML tidak di-escape
            ->make(true);
    }

    // Ambil data untuk tampilan awal
    $pengajuanMagang = PengajuanMagang::with(['profilMahasiswa', 'profilDosen'])
        ->where('dosen_id', Auth::user()->user_id)
        ->get();

    $page = (object)[
        'title' => 'Mahasiswa Bimbingan Magang',
    ];

    $breadcrumb = (object)[];

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
        // Ambil user login
        $user = Auth::user();
        $tingkat_kemampuan = KeahlianLowongan::TINGKAT_KEMAMPUAN;
        $lokasi = $pengajuan->lowonganMagang->lokasi;
        $jarak = LocationService::haversineDistance(
            $lokasi->latitude,
            $lokasi->longitude,
            $pengajuan->profilMahasiswa->lokasi->latitude,
            $pengajuan->profilMahasiswa->lokasi->longitude
        );

        return view('dosen.mahasiswabimbingan.detail', compact('pengajuan', 'page', 'breadcrumb', 'user','tingkat_kemampuan','lokasi','jarak'));
    }
    public function logAktivitas($id)
    {
        $pengajuan = PengajuanMagang::with(['logAktivitas', 'profilMahasiswa'])
            ->where('pengajuan_id', $id)
            ->firstOrFail();

        return view('dosen.mahasiswabimbingan.detail.logAktivitas', compact('pengajuan'));
    }




    public function editProfile()
    {
        $user = auth()->user()->profilDosen;
        $nama = $user->nama;

        $lokasi = Lokasi::all();
        $program = ProgramStudi::all();

        return view('dosen.profile.edit', compact('lokasi', 'program', 'user'))->with('on_complete');
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->user_id;  // ambil id user/dosen yang login

        // Validasi form
        $request->validate([
            'email' => 'nullable|email|unique:user,email,' . $id . ',user_id',
            'nomor_telepon' => 'nullable|string|max:15',
            'minat_penelitian' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lokasi_alamat' => ['required', 'string'],
            'location_latitude' => ['required', 'numeric'],
            'location_longitude' => ['required', 'numeric'],
        ]);

        // Update email di tabel user
        $user = User::find($id);
        if ($user && $request->filled('email')) {
            $user->email = $request->email;
            $user->save();
        }

        // Update profil dosen
        $profilData = $request->only([
            'nomor_telepon',
        ]);

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = 'profile-' . $user->username . '.webp';
            $image->storeAs('public/profile_pictures', $imageName);
            $profilData['foto_profil'] = $imageName;
        }

        $profil = ProfilDosen::where('dosen_id', $id)->first();
        if ($profil) {
            $profil->update($profilData);
        }


        // Update alamat di tabel lokasi berdasarkan dosen_id
        $lokasi = Lokasi::where('lokasi_id', $id)->first();
        if ($profil->lokasi) {
            $profil->lokasi->update([
                'alamat' => $request->lokasi_alamat,
                'latitude' => $request->location_latitude,
                'longitude' => $request->location_longitude
            ]);
        } else {
            $lokasi = Lokasi::create(['alamat' => $request->alamat]);
            $profil->lokasi_id = $lokasi->id;
        }


        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:5'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }
        Auth::user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diubah'
        ]);
    }
    public function simpanFeedback(Request $request)
    {
        $request->validate([
            'log_id' => 'required|exists:log_aktivitas,log_id',
            'feedback' => 'required|string',
        ]);

        $log = LogAktivitas::with('pengajuanMagang')->find($request->log_id);
        $log->feedback_dosen = $request->feedback;
        $log->save();

        $mahasiswa = $log->pengajuanMagang->profilMahasiswa->user;
        $mahasiswa->notify(new UserNotification((object) [
            'title' => 'Dosen memberi feedback',
            'message' => 'Log: ' . $log->tanggal_log,
            'linkTitle' => 'Log Aktivitas',
            'link' => str_replace(url('/'), '', route('mahasiswa.magang.log-aktivitas', $log->pengajuanMagang->pengajuan_id))
        ]));

        return redirect()->back()->with('feedback_success', 'Feedback berhasil disimpan!');
    }
    public function hapusFeedback(Request $request)
    {
        $log = LogAktivitas::findOrFail($request->log_id);
        $log->feedback_dosen = null;
        $log->save();

        return redirect()->back()->with('feedback_success', 'Feedback berhasil dihapus.');
    }
}
