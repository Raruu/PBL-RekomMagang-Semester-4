<?php

namespace App\Http\Controllers;

use App\Models\BidangIndustri;
use App\Models\LowonganMagang;
use App\Models\PerusahaanMitra;
use App\Models\Keahlian;
use App\Models\KeahlianLowongan;
use App\Models\PersyaratanMagang;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLowonganMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $lowongan = LowonganMagang::all();

            return DataTables::of($lowongan)
                ->addIndexColumn()
                ->addColumn('judul_lowongan', fn($row) => $row->judul_lowongan)
                ->addColumn('judul_posisi', fn($row) => $row->judul_posisi)
                ->addColumn('perusahaan', fn($row) => $row->perusahaanMitra->nama_perusahaan ?? '-')
                ->addColumn('lokasi', fn($row) => $row->lokasi->alamat ?? '-')
                ->addColumn('tipe_kerja_lowongan', function ($row) {
                    $tipeKerja = LowonganMagang::TIPE_KERJA[$row->tipe_kerja_lowongan] ?? $row->tipe_kerja_lowongan;
                    $badgeClass = match ($row->tipe_kerja_lowongan) {
                        'remote' => 'primary',
                        'onsite' => 'success',
                        'hybrid' => 'warning',
                        default => 'secondary'
                    };
                    return '<span class="badge bg-' . $badgeClass . '">' . $tipeKerja . '</span>';
                })
                ->addColumn('batas_pendaftaran', function ($row) {
                    return \Carbon\Carbon::parse($row->batas_pendaftaran)->format('d/m/Y');
                })
                ->addColumn('status', function ($row) {
                    $label = $row->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
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

            $lowongan->is_active = !$lowongan->is_active;
            $lowongan->save();

            $status = $lowongan->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json(['message' => "Lowongan {$lowongan->judul_lowongan} berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
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
            \Log::error('Error creating lowongan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
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
            'deskripsi_persyaratan' => 'nullable|string',
            'pengalaman' => ['nullable', 'boolean'],
            'keahlian' => 'required|array|min:1',
            'keahlian.*.id' => 'required|exists:keahlian,keahlian_id',
            'keahlian.*.tingkat' => 'required|in:pemula,menengah,mahir,ahli',
        ]);

        try {
            // Create persyaratan
            $persyaratan = PersyaratanMagang::create([
                'lowongan_id' => $id,
                'minimum_ipk' => $validated['minimum_ipk'],
                'deskripsi_persyaratan' => $validated['deskripsi_persyaratan'],
                'pengalaman' => $validated['pengalaman'] ?? 0,
            ]);

            // Create keahlian
            foreach ($validated['keahlian'] as $keahlian) {
                KeahlianLowongan::create([
                    'lowongan_id' => $id,
                    'keahlian_id' => $keahlian['id'],
                    'kemampuan_minimum' => $keahlian['tingkat'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil dilengkapi.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $lowongan = LowonganMagang::with([
                'perusahaanMitra:perusahaan_id,nama_perusahaan',
                'lokasi:lokasi_id,alamat',
                'persyaratanMagang:persyaratan_id,lowongan_id,minimum_ipk,deskripsi_persyaratan,pengalaman',
                'keahlianLowongan.keahlian:keahlian_id,nama_keahlian'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $lowongan
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching lowongan details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data lowongan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $lowongan = LowonganMagang::findOrFail($id);

            // Check if there are any related pengajuan magang
            if ($lowongan->pengajuanMagang()->count() > 0) {
                return response()->json(['error' => 'Tidak dapat menghapus lowongan yang sudah memiliki pengajuan magang'], 422);
            }

            $judulLowongan = $lowongan->judul_lowongan;
            $lowongan->delete();

            return response()->json(['message' => "Lowongan {$judulLowongan} berhasil dihapus!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus lowongan: ' . $e->getMessage()], 500);
        }
    }
}