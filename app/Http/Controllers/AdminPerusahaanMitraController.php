<?php

namespace App\Http\Controllers;

use App\Models\BidangIndustri;
use App\Models\PerusahaanMitra;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminPerusahaanMitraController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PerusahaanMitra::with('lokasi')->orderByDesc('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_perusahaan', fn($row) => $row->nama_perusahaan ?? '-')
                ->addColumn('bidang_industri', fn($row) => $row->bidangIndustri->nama ?? '-')
                ->addColumn('alamat', fn($row) => $row->lokasi->alamat ?? '-')
                ->addColumn('status', function ($row) {
                    $label = $row->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    return (object)[
                        'perusahaan_id' => $row->perusahaan_id,
                        'status' => $row->is_active,
                        'nama_perusahaan' => $row->nama_perusahaan,
                    ];
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $page = (object)[
            'title' => 'Manajemen Perusahaan Mitra',
        ];

        $breadcrumb = (object)[
            'title' => 'Daftar Perusahaan',
            'list' => ['Master Data', 'Perusahaan Mitra'],
        ];

        return view('admin.perusahaan.mitra.index', compact('page', 'breadcrumb'));
    }

    public function create()
    {
        $lokasis = Lokasi::all();
        $bidangIndustri = BidangIndustri::all();
        return view('admin.perusahaan.mitra.create', compact('lokasis', 'bidangIndustri'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lokasi_alamat' => 'required',
                'location_longitude' => 'required',
                'location_latitude' => 'required',
                'nama_perusahaan' => 'required|max:100',
                'bidang_id' => 'nullable|max:100',
                'website' => 'nullable|url|max:255',
                'kontak_email' => 'nullable|email|max:100',
                'kontak_telepon' => 'nullable|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            DB::beginTransaction();

            $lokasi = Lokasi::create([
                'alamat' => $request->lokasi_alamat,
                'latitude' => $request->location_latitude,
                'longitude' => $request->location_longitude
            ]);

            $dataPerusahaanMitra = $request->only([
                'nama_perusahaan',
                'bidang_id',
                'website',
                'kontak_email',
                'kontak_telepon',
            ]);
            $dataPerusahaanMitra['lokasi_id'] = $lokasi->lokasi_id;

            PerusahaanMitra::create($dataPerusahaanMitra);

            DB::commit();
            return response()->json(['message' => 'Perusahaan berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $perusahaan = PerusahaanMitra::with('lokasi')->findOrFail($id);
        return view('admin.perusahaan.mitra.show', compact('perusahaan'));
    }

    public function edit($id)
    {
        $perusahaan = PerusahaanMitra::findOrFail($id);
        $lokasi = $perusahaan->lokasi;
        $bidangIndustri = BidangIndustri::all();
        return view('admin.perusahaan.mitra.edit', compact('perusahaan', 'lokasi', 'bidangIndustri'));
    }

    public function update(Request $request, $id)
    {
        $perusahaan = PerusahaanMitra::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'lokasi_alamat' => 'required',
            'location_longitude' => 'required',
            'location_latitude' => 'required',
            'nama_perusahaan' => 'required|max:100',
            'bidang_id' => 'nullable|max:100',
            'website' => 'nullable|url|max:255',
            'kontak_email' => 'nullable|email|max:100',
            'kontak_telepon' => 'nullable|max:20',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lokasi = $perusahaan->lokasi;
        $lokasi->update([
            'alamat' => $request->lokasi_alamat,
            'latitude' => $request->location_latitude,
            'longitude' => $request->location_longitude
        ]);

        $dataPerusahaanMitra = $request->only([
            'nama_perusahaan',
            'bidang_id',
            'website',
            'kontak_email',
            'kontak_telepon',
            'is_active',
        ]);
        $perusahaan->update($dataPerusahaanMitra);

        return response()->json(['message' => 'Perusahaan berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        try {
            $perusahaan = PerusahaanMitra::findOrFail($id);
            $perusahaan->delete();

            return response()->json(['message' => 'Perusahaan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['message' =>  $e->getMessage()], 500);
        }
    }


    public function toggleStatus($id)
    {
        try {
            $perusahaan = PerusahaanMitra::findOrFail($id);
            $perusahaan->is_active = !$perusahaan->is_active;
            $perusahaan->save();

            $status = $perusahaan->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json(['message' => "Status perusahaan berhasil {$status}!"]);
        } catch (\Exception $e) {
            return response()->json(['message' =>  $e->getMessage()], 500);
        }
    }
}
