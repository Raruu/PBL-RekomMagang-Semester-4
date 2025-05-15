<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanMitra;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanMitraController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PerusahaanMitra::with('lokasi')->orderByDesc('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_perusahaan', fn($row) => $row->nama_perusahaan ?? '-')
                ->addColumn('bidang_industri', fn($row) => $row->bidang_industri ?? '-')
                ->addColumn('website', fn($row) => $row->website ?? '-')
                ->addColumn('kontak_email', fn($row) => $row->kontak_email ?? '-')
                ->addColumn('kontak_telepon', fn($row) => $row->kontak_telepon ?? '-')
                ->addColumn('alamat', fn($row) => $row->lokasi->alamat ?? '-')
                ->addColumn('status', function ($row) {
                    $label = $row->is_active ? 'Aktif' : 'Nonaktif';
                    $class = $row->is_active ? 'success' : 'danger';
                    return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                })
                ->addColumn('aksi', function ($row) {
    $statusBtn = '<button type="button" class="btn btn-sm btn-' . 
        ($row->is_active ? 'success' : 'secondary') . ' toggle-status-btn" ' .
        'data-perusahaan_id="' . $row->perusahaan_id . '" ' .
        'data-nama_perusahaan="' . $row->nama_perusahaan . '" ' .
        'title="' . ($row->is_active ? 'Nonaktifkan' : 'Aktifkan') . '">' .
        '<i class="fas fa-' . ($row->is_active ? 'toggle-on' : 'toggle-off') . '"></i></button>';

    return '
        <div class="btn-group" role="group">
            <a href="' . url('/admin/perusahaan/' . $row->perusahaan_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
            <a href="' . url('/admin/perusahaan/' . $row->perusahaan_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
            ' . $statusBtn . '
            <form action="' . url('/admin/perusahaan/' . $row->perusahaan_id) . '" method="POST" class="d-inline delete-form">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div>';
})
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        $page = (object)[
            'title' => 'Manajemen Perusahaan Mitra',
        ];

        $breadcrumb = (object)[
            'title' => 'Daftar Perusahaan',
            'list' => ['Master Data', 'Perusahaan Mitra'],
        ];

        return view('admin.perusahaan.index', compact('page', 'breadcrumb'));
    }

    public function create()
    {
        $lokasis = Lokasi::all();
        return view('admin.perusahaan.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'nama_perusahaan' => 'required|max:100',
            'bidang_industri' => 'nullable|max:100',
            'website' => 'nullable|url|max:255',
            'kontak_email' => 'nullable|email|max:100',
            'kontak_telepon' => 'nullable|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $perusahaan = PerusahaanMitra::create($validator->validated());

        return response()->json(['message' => 'Perusahaan berhasil ditambahkan!']);
    }

    public function show($id)
    {
        $perusahaan = PerusahaanMitra::with('lokasi')->findOrFail($id);
        return view('admin.perusahaan.show', compact('perusahaan'));
    }

    public function edit($id)
    {
        $perusahaan = PerusahaanMitra::findOrFail($id);
        $lokasis = Lokasi::all();
        return view('admin.perusahaan.edit', compact('perusahaan', 'lokasis'));
    }

    public function update(Request $request, $id)
    {
        $perusahaan = PerusahaanMitra::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'nama_perusahaan' => 'required|max:100',
            'bidang_industri' => 'nullable|max:100',
            'website' => 'nullable|url|max:255',
            'kontak_email' => 'nullable|email|max:100',
            'kontak_telepon' => 'nullable|max:20',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $perusahaan->update($validator->validated());

        // return response()->json(['message' => 'Perusahaan berhasil diperbarui!']);
        return redirect('/admin/perusahaan')
    ->with('success', 'Perusahaan berhasil diperbarui!');

    }

    public function destroy($id)
{
    try {
        $perusahaan = PerusahaanMitra::findOrFail($id);
        $perusahaan->delete();

        return redirect('/admin/perusahaan')
            ->with('success', 'Perusahaan berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect('/admin/perusahaan')
            ->with('error', 'Gagal menghapus perusahaan: ' . $e->getMessage());
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
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}
