<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminKategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::orderByDesc('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kategori', fn($row) => $row->nama_kategori ?? '-')
                ->addColumn('deskripsi', fn($row) => $row->deskripsi ?? '-')
                ->addColumn('aksi', function ($row) {
                    return (object)[
                        'kategori_id' => $row->kategori_id,
                        'nama_kategori' => $row->nama_kategori,
                    ];
                })
                ->make(true);
        }

        $page = (object)[
            'title' => 'Manajemen Kategori',
        ];

        $breadcrumb = (object)[
            'title' => 'Daftar Kategori',
            'list' => ['Master Data', 'Kategori'],
        ];

        return view('admin.keahlian.kategori.index', compact('page', 'breadcrumb'));
    }

    public function create()
    {
        return view('admin.keahlian.kategori.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'msgField' => $validator->errors()], 422);
        }

        Kategori::create($request->only(['nama_kategori', 'deskripsi']));

        return response()->json(['message' => 'Kategori berhasil ditambahkan!'], 200);
    }

    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.keahlian.kategori.show', compact('kategori'));
    }



    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.keahlian.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'msgField' => $validator->errors()], 422);
        }

        $kategori->update($request->only(['nama_kategori', 'deskripsi']));

        return response()->json(['message' => 'Kategori berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return response()->json(['message' => 'Kategori berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['message' =>  $e->getMessage()], 500);
        }
    }
}
