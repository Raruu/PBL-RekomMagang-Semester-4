<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfilMahasiswa;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class AdminProfilMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProfilMahasiswa::with(['user', 'programStudi'])
                ->whereHas('user', fn($q) => $q->where('role', 'mahasiswa'));
            
            $filter = $request->get('filter');
            $filterVerif = $request->get('filter_verif');

            if ($filterVerif) {
                if ($filterVerif === 'verified') {
                    $query->where('verified', 1);
                } elseif ($filterVerif === 'unverified') {
                    $query->where('verified', 0)->where(function($q) {
                        $q->whereNull('file_transkrip_nilai')->orWhere('file_transkrip_nilai', '');
                    });
                } elseif ($filterVerif === 'meminta_verif') {
                    $query->where('verified', 0)->whereNotNull('file_transkrip_nilai')->where('file_transkrip_nilai', '!=', '');
                }
            } else if ($filter) {
                if ($filter === 'active') {
                    $query->whereHas('user', fn($q) => $q->where('is_active', 1));
                } elseif ($filter === 'inactive') {
                    $query->whereHas('user', fn($q) => $q->where('is_active', 0));
                }
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nim', fn($row) => $row->nim)
                ->addColumn('nama', fn($row) => $row->nama)
                ->addColumn('email', fn($row) => $row->user->email)
                ->addColumn('program_studi', fn($row) => $row->programStudi->nama_program ?? '-')
                ->addColumn('angkatan', fn($row) => $row->angkatan ?? '-')
                ->addColumn('status', function ($row) {
                    $class = $row->user->is_active ? 'success' : 'danger';
                    $label = $row->user->is_active ? 'Aktif' : 'Nonaktif';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('status_verif', function ($row) {
                    if ($row->verified) {
                        return '<span class="badge bg-info">Terverifikasi</span>';
                    } elseif (!$row->verified && $row->file_transkrip_nilai) {
                        return '<span class="badge bg-warning">Meminta Verifikasi</span>';
                    } else {
                        return '<span class="badge bg-secondary">Belum Terverifikasi</span>';
                    }
                })
                ->addColumn('aksi', function ($row) {
                    $verifikasiBtn = '<button type="button" class="toggle-verifikasi-btn btn btn-sm verify-btn btn-success"' .
                        'data-id="' .  $row->user->user_id . '" ' .
                        'data-file="' . $row->file_transkrip_nilai . '" ' .
                        'title="Verifikasi">' .
                        '<i class="fas fa-user-check"></i></button>';

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
                        ($row->verified == 0 && $row->file_transkrip_nilai != null ? $verifikasiBtn : '') . $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->rawColumns(['status', 'status_verif', 'aksi'])
                ->make(true);
        }

        $page = (object) [
            'title' => 'Manajemen Profil Mahasiswa',
        ];

        return view('admin.profil_mahasiswa.index', compact('page'));
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
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('user')->ignore($id, 'user_id'),
                Rule::unique('profil_mahasiswa', 'nim')->where(function ($query) use ($id) {
                    return $query->where('mahasiswa_id', '!=', $id);
                })
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
            'ipk' => 'nullable|numeric|between:0,4.00',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::where('user_id', $id)->where('role', 'mahasiswa')->firstOrFail();
            $user->username = $request->username;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            ProfilMahasiswa::updateOrCreate(
                ['mahasiswa_id' => $id],
                [
                    'nama' => $request->nama,
                    'nim' => $request->username,
                    'program_id' => $request->program_id,
                    'angkatan' => $request->angkatan,
                    'ipk' => $request->ipk,
                    'lokasi_id' => $request->lokasi_id ?? 1,
                    'alamat' => $request->alamat ?? ''
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data mahasiswa berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
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
            return response()->json(['error' => 'Gagal menghapus data, data sedang dipakai!', 'console' => $e->getMessage()], 500);
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

    public function getDataVerifikasiMahasiswa($id)
    {
        try {
            $mahasiswa = ProfilMahasiswa::where('mahasiswa_id', $id)->firstOrFail();

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $file = 'dokumen/mahasiswa/transkrip_nilai/' . $mahasiswa->getRawOriginal('file_transkrip_nilai');
            $file = storage_path('app/public/' . $file);

            if (!file_exists($file)) {
                return response()->json(['message' => 'File transkrip nilai tidak ditemukan', 'file' => $file], 404);
            }

            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $result = [];

            if (count($data) > 1) {
                foreach ($data as $col => $value) {
                    if ($col > 1) {
                        $isNotValid = !is_numeric($value['B']) || $value['B'] > 4;
                        $result[] = [
                            'semester' => $value['A'],
                            'ipk' => $isNotValid ? 'Data tidak Valid' : $value['B'],
                        ];
                        if ($isNotValid) {
                            return response()->json(['data' => $result, 'isNotValid' => true, 'message' => 'Data tidak valid']);
                        }
                    }
                }
            } else {
                return response()->json(['message' => 'File transkrip bernilai kosong'], 422);
            }

            return response()->json(['data' => $result, 'isNotValid' => false]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Kesalahan pada server', 'console' => $e->getMessage()], 500);
        }
    }

    public function verfikasiMahasiswaReject($id)
    {
        try {
            $mahasiswa = ProfilMahasiswa::where('mahasiswa_id', $id)->firstOrFail();
            $filePathInStorage = 'public/dokumen/mahasiswa/transkrip_nilai/'  . $mahasiswa->getRawOriginal('file_transkrip_nilai');
            if (Storage::exists($filePathInStorage)) {
                Storage::delete($filePathInStorage);
            }
            $mahasiswa->update([
                'file_transkrip_nilai' => null,
                'verified' => false
            ]);

            $mahasiswa->user->notify(new UserNotification((object) [
                'title' => 'Varifikasi Gagal',
                'message' => 'Verifikasi dokumen transkrip nilai ditolak oleh admin',
                'linkTitle' => 'Upload lagi',
                'link' =>  str_replace(url('/'), '', route('mahasiswa.dokumen'))
            ]));

            return response()->json(['message' => 'Mahasiswa berhasil direject!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Kesalahan pada server', 'console' => $e->getMessage()], 500);
        }
    }

    public function verfikasiMahasiswa($id)
    {
        DB::beginTransaction();
        try {
            $mahasiswa = ProfilMahasiswa::where('mahasiswa_id', $id)->firstOrFail();

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $file = 'dokumen/mahasiswa/transkrip_nilai/' . $mahasiswa->getRawOriginal('file_transkrip_nilai');
            $file = storage_path('app/public/' . $file);
            if (!file_exists($file)) {
                return response()->json(['message' => 'File transkrip nilai tidak ditemukan', 'file' => $file], 404);
            }
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $semester = 1;
            $ipk = 0;

            if (count($data) > 1) {
                $totalLength = count($data) - 1;
                foreach ($data as $col => $value) {
                    if ($col > 1) {
                        $semester = (float) ($value['A'] ?? 1);
                        $ipk += (float) ($value['B'] ?? 0);
                        if (!is_numeric($value['B']) || $value['B'] > 4) {
                            return response()->json(['message' => 'Data tidak valid, Tolong tolak saja'], 422);
                        }
                    }
                }
            } else {
                return response()->json(['message' => 'File transkrip bernilai kosong'], 422);
            }

            $mahasiswa->ipk = $ipk / $totalLength;
            $mahasiswa->angkatan = date('Y') - $semester + 2;
            $mahasiswa->verified = true;
            $mahasiswa->save();

            $nama = $mahasiswa->nama;
            $mahasiswa->user->notify(new UserNotification((object)[
                'title' => 'Akun Diverifikasi',
                'message' => "Akun Anda sudah diverifikasi oleh admin",
                'linkTitle' => '',
                'link' => ''
            ]));

            DB::commit();

            return response()->json(['message' => "Akun {$nama} Berhasil Diverifikasi!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Kesalahan pada Server',
                'console' =>  $e->getMessage()
            ], 500);
        }
    }
}
