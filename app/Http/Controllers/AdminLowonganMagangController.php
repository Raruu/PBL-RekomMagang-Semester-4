<?php

namespace App\Http\Controllers;

use App\Models\BidangIndustri;
use App\Models\LowonganMagang;
use App\Models\PerusahaanMitra;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\KeahlianMahasiswa;
use App\Models\PersyaratanMagang;
use App\Models\Lokasi;
use App\Models\FeedbackMahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLowonganMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $lowongan = LowonganMagang::query()
                ->leftJoin('perusahaan as perusahaan_mitra', 'lowongan_magang.perusahaan_id', '=', 'perusahaan_mitra.perusahaan_id')
                ->leftJoin('lokasi', 'lowongan_magang.lokasi_id', '=', 'lokasi.lokasi_id')
                ->select('lowongan_magang.*', 'perusahaan_mitra.nama_perusahaan', 'lokasi.alamat');

            $filter = $request->get('filter');
            if ($filter === 'active') {
                $lowongan->where('lowongan_magang.is_active', 1);
            } elseif ($filter === 'inactive') {
                $lowongan->where('lowongan_magang.is_active', 0);
            }

            return DataTables::of($lowongan)
                ->addIndexColumn()
                ->addColumn('judul_lowongan', fn($row) => $row->judul_lowongan)
                ->addColumn('judul_posisi', fn($row) => $row->judul_posisi)
                ->addColumn('perusahaan', fn($row) => $row->nama_perusahaan ?? '-')
                ->addColumn('lokasi', fn($row) => $row->alamat ?? '-')
                ->addColumn('tipe_kerja_lowongan', function ($row) {
                    $tipeKerja = LowonganMagang::TIPE_KERJA[$row->tipe_kerja_lowongan] ?? $row->tipe_kerja_lowongan;
                    $badgeClass = match ($row->tipe_kerja_lowongan) {
                        'remote' => 'primary',
                        'onsite' => 'success',
                        'hybrid' => 'warning',
                        default => 'secondary',
                    };
                    return '<span class="badge bg-' . $badgeClass . '">' . $tipeKerja . '</span>';
                })
                ->addColumn('batas_pendaftaran', function ($row) {
                    return \Carbon\Carbon::parse($row->batas_pendaftaran)->format('d/m/Y');
                })
                ->addColumn('status', function ($row) {
                    $label = $row->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->is_active ? 'success' : 'danger';
                    $num = $row->is_active ? '1' : '0';
                    return '<span class="badge bg-' . $class . '" id="' . $num . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $viewBtn = '<button type="button" class="btn btn-info btn-sm view-btn" ' .
                        'data-url="' . route('admin.magang.lowongan.show', $row->lowongan_id) . '" ' .
                        'title="Lihat Detail">' .
                        '<i class="fas fa-eye"></i></button>';

                    $editBtn = '<button type="button" class="btn btn-warning btn-sm edit-btn" ' .
                        'data-url="' . route('admin.magang.lowongan.edit', $row->lowongan_id) . '" ' .
                        'title="Edit Lowongan">' .
                        '<i class="fas fa-edit"></i></button>';

                    $statusBtn = '<button type="button" class="toggle-status-btn btn btn-sm btn-' .
                        ($row->is_active ? 'success' : 'secondary') . '" ' .
                        'data-lowongan-id="' . $row->lowongan_id . '" ' .
                        'data-judul="' . $row->judul_lowongan . '" ' .
                        'title="' . ($row->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
                        '<i class="fas fa-' . ($row->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm delete-btn" ' .
                        'data-url="' . route('admin.magang.lowongan.destroy', $row->lowongan_id) . '" ' .
                        'data-judul="' . $row->judul_lowongan . '" ' .
                        'title="Hapus Lowongan">' .
                        '<i class="fas fa-trash"></i></button>';

                    return '<div class="action-btn-group d-flex flex-wrap justify-content-center flex-row">' .
                        $viewBtn . $editBtn . $statusBtn . $deleteBtn .
                        '</div>';
                })
                ->filterColumn('perusahaan', function ($query, $keyword) {
                    $query->where('perusahaan_mitra.nama_perusahaan', 'like', "%{$keyword}%");
                })
                ->filterColumn('lokasi', function ($query, $keyword) {
                    $query->where('lokasi.alamat', 'like', "%{$keyword}%");
                })
                ->filterColumn('judul_lowongan', function ($query, $keyword) {
                    $query->where('lowongan_magang.judul_lowongan', 'like', "%{$keyword}%");
                })
                ->filterColumn('judul_posisi', function ($query, $keyword) {
                    $query->where('lowongan_magang.judul_posisi', 'like', "%{$keyword}%");
                })
                ->rawColumns(['tipe_kerja_lowongan', 'status', 'aksi'])
                ->make(true);
        }

        $page = (object) [
            'title' => 'Manajemen Lowongan Magang',
        ];

        return view('admin.magang.lowongan.index', compact('page'));
    }

    public function toggleStatus($id)
    {
        try {
            $lowongan = LowonganMagang::findOrFail($id);

            if (!$lowongan->is_active) {
                $persyaratan = PersyaratanMagang::where('lowongan_id', $id)->first();

                $keahlian = KeahlianLowongan::where('lowongan_id', $id)->count();

                if (!$persyaratan || $keahlian == 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Persyaratan dan keahlian belum lengkap',
                        'redirect_url' => route('admin.magang.lowongan.lanjutan', $id),
                        'action' => 'redirect'
                    ], 422);
                }
            }

            $lowongan->is_active = !$lowongan->is_active;
            $lowongan->save();

            $status = $lowongan->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json([
                'success' => true,
                'message' => "Lowongan {$lowongan->judul_lowongan} berhasil {$status}!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'error' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function deactivateForBack($id)
    {
        try {
            $lowongan = LowonganMagang::findOrFail($id);

            $lowongan->is_active = false;
            $lowongan->save();

            return response()->json([
                'success' => true,
                'message' => "Lowongan {$lowongan->judul_lowongan} telah dinonaktifkan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'error' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $perusahaanList = PerusahaanMitra::with('lokasi', 'bidangIndustri')
            ->where('is_active', true)
            ->get();

        $lokasiList = Lokasi::all();
        $bidangList = BidangIndustri::all();

        $page = (object) [
            'title' => 'Tambah Lowongan Magang',
        ];

        return view(
            'admin.magang.lowongan.create',
            compact('perusahaanList', 'lokasiList', 'bidangList', 'page')
        );
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'perusahaan_id' => 'required|exists:perusahaan,perusahaan_id',
                'lokasi_id' => 'required|exists:lokasi,lokasi_id',
                'judul_lowongan' => 'required|string|max:255',
                'judul_posisi' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'gaji' => 'nullable|numeric',
                'kuota' => 'required|integer|min:1',
                'tipe_kerja_lowongan' => 'required|in:remote,onsite,hybrid',
                'batas_pendaftaran' => 'required|date|after:today',
                'is_active' => 'nullable|boolean',
            ]);

            $validated['is_active'] = $request->has('is_active');

            $lowongan = LowonganMagang::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil ditambahkan.',
                'lowongan_id' => $lowongan->lowongan_id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function formLanjutan($id)
    {
        $lowongan = LowonganMagang::findOrFail($id);
        $keahlianList = Keahlian::all();

        $page = (object) [
            'title' => 'Persyaratan dan Keahlian    ',
        ];

        return view('admin.magang.lowongan.lanjutan', compact('lowongan', 'keahlianList', 'page'));
    }

    public function storeLanjutan(Request $request, $id)
    {
        $validated = $request->validate([
            'minimum_ipk' => 'nullable|numeric|min:0|max:4',
            'deskripsi_persyaratan' => 'required|string',
            'pengalaman' => ['nullable', 'boolean'],
            'dokumen_persyaratan' => 'nullable|string',
            'keahlian' => 'required|array|min:1',
            'keahlian.*.id' => 'required|exists:keahlian,keahlian_id',
            'keahlian.*.tingkat' => 'required|in:pemula,menengah,mahir,ahli',
        ]);

        try {
            $dokumenPersyaratan = $validated['dokumen_persyaratan'];
            foreach (explode(';', $dokumenPersyaratan) as $dokumen) {
                if (strlen($dokumen) > 50) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dokumen persyaratan tidak boleh lebih dari 50 karakter, dokumen: ' . $dokumen
                    ], 422);
                }
            }
            $dokumenArr = array_filter(array_map('trim', explode(';', $dokumenPersyaratan)), fn($v) => $v !== '');
            $dokumenPersyaratan = '';
            if (!empty($dokumenArr)) {
                $dokumenPersyaratan = implode(';', $dokumenArr);
            }

            $persyaratan = PersyaratanMagang::create([
                'lowongan_id' => $id,
                'minimum_ipk' => $validated['minimum_ipk'],
                'deskripsi_persyaratan' => $validated['deskripsi_persyaratan'],
                'dokumen_persyaratan' => $dokumenPersyaratan,
                'pengalaman' => $validated['pengalaman'] ?? 0,
            ]);

            foreach ($validated['keahlian'] as $keahlian) {
                KeahlianLowongan::create([
                    'lowongan_id' => $id,
                    'keahlian_id' => $keahlian['id'],
                    'kemampuan_minimum' => $keahlian['tingkat'],
                ]);
            }

            LowonganMagang::where('lowongan_id', $id)->update([
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil dilengkapi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $lowongan = LowonganMagang::with([
                'perusahaanMitra:perusahaan_id,nama_perusahaan',
                'lokasi:lokasi_id,alamat',
                'persyaratanMagang:persyaratan_id,lowongan_id,minimum_ipk,deskripsi_persyaratan,dokumen_persyaratan,pengalaman',
                'keahlianLowongan.keahlian:keahlian_id,nama_keahlian'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $lowongan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $lowongan = LowonganMagang::with([
                'perusahaanMitra:perusahaan_id,nama_perusahaan',
                'lokasi:lokasi_id,alamat',
                'persyaratanMagang:persyaratan_id,lowongan_id,minimum_ipk,deskripsi_persyaratan,dokumen_persyaratan,pengalaman',
                'keahlianLowongan.keahlian:keahlian_id,nama_keahlian'
            ])->findOrFail($id);

            $perusahaanList = PerusahaanMitra::where('is_active', true)->get();
            $lokasiList = Lokasi::all();
            $keahlianList = $lowongan->keahlianLowongan;
            $tingkat_kemampuan = KeahlianMahasiswa::TINGKAT_KEMAMPUAN;

            return response()->json([
                'data' => view('admin.magang.lowongan.edit', [
                    'lowongan' => $lowongan,
                    'perusahaanList' => $perusahaanList,
                    'lokasiList' => $lokasiList,
                    'keahlianList' => $keahlianList,
                    'tipeKerja' => LowonganMagang::TIPE_KERJA,
                    'tingkat_kemampuan' => $tingkat_kemampuan,
                ])->render(),
                'tingkat_kemampuan' => array_keys($tingkat_kemampuan),
                'keahlianList' => Keahlian::all()->pluck('nama_keahlian')->toArray()
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (str_contains($message, '"minimum_ipk" on null')) {
                return response()->json([
                    'title' => 'Persyaratan belum lengkap',
                    'message' => 'Lengkapi persyaratan terlebih dahulu',
                    'id' => $id,
                ], 406);
            }
            return response()->json([
                'message' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'perusahaan_id' => 'required',
                'lokasi_id' => 'required|exists:lokasi,lokasi_id',
                'judul_lowongan' => 'required|string|max:255',
                'judul_posisi' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'gaji' => 'nullable|numeric',
                'kuota' => 'required|integer|min:1',
                'tipe_kerja_lowongan' => 'required|in:remote,onsite,hybrid',
                'batas_pendaftaran' => 'required|date|after:today',
                'is_active' => 'nullable|boolean',
                // Persyaratan
                'minimum_ipk' => 'nullable|numeric|min:0|max:4',
                'deskripsi_persyaratan' => 'required|string',
                'pengalaman' => 'nullable|boolean',
                'dokumen_persyaratan' => 'nullable|string',
            ]);

            foreach (explode(';', $request->dokumen_persyaratan) as $dokumen) {
                if (strlen($dokumen) > 50) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dokumen persyaratan tidak boleh lebih dari 50 karakter, dokumen: ' . $dokumen
                    ], 422);
                }
            }

            $lowongan = LowonganMagang::findOrFail($id);

            $validated['is_active'] = $request->has('is_active');
            $lowongan->update($validated);

            $persyaratanData = [
                'minimum_ipk' => $validated['minimum_ipk'],
                'deskripsi_persyaratan' => $validated['deskripsi_persyaratan'],
                'pengalaman' => $validated['pengalaman'] ?? 0,
                'dokumen_persyaratan' => $validated['dokumen_persyaratan'],
            ];

            PersyaratanMagang::updateOrCreate(
                ['lowongan_id' => $id],
                $persyaratanData
            );

            $keahlianNew = [];
            $levels = array_keys(KeahlianMahasiswa::TINGKAT_KEMAMPUAN);
            foreach ($levels as $level) {
                $keahlian = collect(json_decode($request->input("keahlian")[$level], true))->pluck('value')->toArray();
                foreach ($keahlian as $keahlianNama) {
                    $keahlianNew[] = $keahlianNama;
                    $keahlianRecord = Keahlian::where('nama_keahlian', $keahlianNama)->first();
                    KeahlianLowongan::updateOrCreate(
                        ['lowongan_id' => $id, 'keahlian_id' => $keahlianRecord->keahlian_id],
                        ['kemampuan_minimum' => $level]
                    );
                }
            }

            $keahlianOld = KeahlianLowongan::where('lowongan_id', $id)
                ->get()->pluck('keahlian.nama_keahlian')->toArray();
            $toDeleteKeahlian = array_diff($keahlianOld, $keahlianNew);
            if (!empty($toDeleteKeahlian)) {
                $keahlianIdsToDelete = Keahlian::whereIn('nama_keahlian', $toDeleteKeahlian)->pluck('keahlian_id');
                KeahlianLowongan::where('lowongan_id', $id)
                    ->whereIn('keahlian_id', $keahlianIdsToDelete)
                    ->delete();
            }

            return response()->json([
                'message' => 'Lowongan berhasil diperbarui.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $lowongan = LowonganMagang::findOrFail($id);

            if ($lowongan->pengajuanMagang()->count() > 0) {
                return response()->json(['error' => 'Tidak dapat menghapus lowongan yang sudah memiliki pengajuan magang'], 422);
            }

            $judulLowongan = $lowongan->judul_lowongan;
            $lowongan->delete();

            return response()->json(['message' => "Lowongan {$judulLowongan} berhasil dihapus!"]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Kesalahan pada Server',
                'error' => 'Kesalahan pada Server',
                'console' => $e->getMessage()
            ], 500);
        }
    }

    public function feedback($id)
    {
        $feedbacks = FeedbackMahasiswa::whereHas('pengajuanMagang', function ($q) use ($id) {
            $q->where('lowongan_id', $id);
        })->with(['pengajuanMagang.profilMahasiswa'])->latest()->get();

        $data = $feedbacks->map(function ($item) {
            $profil = optional($item->pengajuanMagang->profilMahasiswa);
            return [
                'feedback_id' => $item->feedback_id,
                'rating' => $item->rating,
                'komentar' => $item->komentar,
                'pengalaman_belajar' => $item->pengalaman_belajar,
                'kendala' => $item->kendala,
                'saran' => $item->saran,
                'mahasiswa' => $profil->nama ?? '-',
                'nim' => $profil->nim ?? '-',
                'foto_profil' => $profil->foto_profil ?? null,
                'created_at' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : null,
            ];
        });
        return response()->json(['data' => $data]);
    }
}
