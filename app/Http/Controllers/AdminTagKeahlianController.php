<?php

namespace App\Http\Controllers;

use App\Models\Keahlian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminTagKeahlianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Keahlian::with('kategori')->orderByDesc('created_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('keahlian_id', fn($row) => $row->keahlian_id)
                ->addColumn('nama_keahlian', fn($row) => $row->nama_keahlian ?? '-')
                ->addColumn('nama_kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
                ->addColumn('deskripsi', fn($row) => $row->deskripsi ?? '-')
                ->addColumn('aksi', function ($row) {
                    return (object)[
                        'keahlian_id' => $row->keahlian_id,
                        'nama_keahlian' => $row->nama_keahlian,
                    ];
                })
                ->make(true);
        }

        $page = (object)[
            'title' => 'Manajemen Tag Keahlian',
        ];

        $breadcrumb = (object)[
            'title' => 'Daftar Tag Keahlian',
            'list' => ['Master Data', 'Tag Keahlian'],
        ];

        return view('admin.keahlian.tag_keahlian.index', compact('page', 'breadcrumb'));
    }

    public function create()
    {
        $kategoriList = \App\Models\Kategori::all();
        return view('admin.keahlian.tag_keahlian.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_keahlian' => 'required|string|max:100',
            'kategori_id' => 'required|integer|exists:kategori_keahlian,kategori_id',
            'deskripsi' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ], 422);
        }

        Keahlian::create($request->only(['nama_keahlian', 'kategori_id', 'deskripsi']));

        return response()->json(['message' => 'Tag keahlian berhasil ditambahkan!'], 200);
    }

    public function show($keahlian_id)
    {
        $tag_keahlian = Keahlian::with('kategori')->findOrFail($keahlian_id);
        return view('admin.keahlian.tag_keahlian.show', compact('tag_keahlian'));
    }

    public function edit($keahlian_id)
    {
        $tag_keahlian = Keahlian::findOrFail($keahlian_id);
        $kategoriList = \App\Models\Kategori::all();

        return view('admin.keahlian.tag_keahlian.edit', compact('tag_keahlian', 'kategoriList'));
    }

    public function update(Request $request, $keahlian_id)
    {
        $tag_keahlian = Keahlian::findOrFail($keahlian_id);

        $validator = Validator::make($request->all(), [
            'nama_keahlian' => 'required|max:100',
            'kategori_id' => 'required|exists:kategori_keahlian,kategori_id',
            'deskripsi' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ], 422);
        }

        $tag_keahlian->update($request->only(['nama_keahlian', 'kategori_id', 'deskripsi']));

        return response()->json(['message' => 'Tag keahlian berhasil diperbarui!']);
    }

    public function destroy($keahlian_id)
    {
        try {
            $tag_keahlian = Keahlian::findOrFail($keahlian_id);
            $tag_keahlian->delete();

            return response()->json(['message' => 'Tag keahlian berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
