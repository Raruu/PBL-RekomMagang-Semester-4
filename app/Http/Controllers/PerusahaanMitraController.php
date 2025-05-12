<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanMitra;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;


class PerusahaanMitraController extends Controller
{
    public function index()
    {
        return view('perusahaan.index');
    }

    public function list(Request $request)
{
    if ($request->ajax()) {
         $data = PerusahaanMitra::join('lokasi', 'perusahaan.lokasi_id', '=', 'lokasi.lokasi_id')
        ->select(
            'perusahaan.*',
            'lokasi.alamat'
        )
        ->orderBy('perusahaan.created_at', 'desc')
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $editUrl = route('perusahaan.edit', $row->perusahaan_id);
                $deleteUrl = route('perusahaan.destroy', $row->perusahaan_id);

                return '
                    <div class="d-flex justify-content-center">
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning me-1">Edit</a>
                        <form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus?\')" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}

    public function create()
    {
        $lokasis = Lokasi::all();
        return view('perusahaan.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'nama_perusahaan' => 'required|max:100',
            'bidang_industri' => 'nullable|max:100',
            'website' => 'nullable|url|max:255',
            'kontak_email' => 'nullable|email|max:100',
            'kontak_telepon' => 'nullable|max:20',
            'is_active' => 'required|boolean',
        ]);

        PerusahaanMitra::create($request->all());

        return redirect()->route('perusahaan.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $perusahaan = PerusahaanMitra::findOrFail($id);
        $lokasis = Lokasi::all();
        return view('perusahaan.edit', compact('perusahaan', 'lokasis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi_id' => 'required|exists:lokasi,lokasi_id',
            'nama_perusahaan' => 'required|max:100',
            'bidang_industri' => 'nullable|max:100',
            'website' => 'nullable|url|max:255',
            'kontak_email' => 'nullable|email|max:100',
            'kontak_telepon' => 'nullable|max:20',
            'is_active' => 'required|boolean',
        ]);

        $perusahaan = PerusahaanMitra::findOrFail($id);
        $perusahaan->update($request->all());

        return redirect()->route('perusahaan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PerusahaanMitra::findOrFail($id)->delete();
        return redirect()->route('perusahaan.index')->with('success', 'Data berhasil dihapus.');
    }
}
