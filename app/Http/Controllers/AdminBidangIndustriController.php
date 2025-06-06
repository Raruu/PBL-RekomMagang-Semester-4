<?php

namespace App\Http\Controllers;

use App\Models\BidangIndustri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminBidangIndustriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BidangIndustri::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('perusahaan', function ($row) {
                    return count($row->perusahaan);
                })
                ->addColumn('aksi', function ($row) {
                    return (object)[
                        'bidang_id' => $row->bidang_id,
                    ];
                })
                ->make(true);
        }
        return view('admin.perusahaan.bidang_industri.index');
    }

    public function show($id)
    {
        $bidangIndustri = BidangIndustri::findOrFail($id);
        $perusahaan = $bidangIndustri->perusahaan;
        return view('admin.perusahaan.bidang_industri.show', ['bidangIndustri' => $bidangIndustri, 'perusahaan' => $perusahaan]);
    }


    public function create()
    {
        return view('admin.perusahaan.bidang_industri.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }

        BidangIndustri::create($request->only('nama'));
        return response()->json([
            'message' => 'Berhasil menambahkan bidang industri'
        ]);
    }

    public function edit($id)
    {
        $bidangIndustri = BidangIndustri::findOrFail($id);
        return view('admin.perusahaan.bidang_industri.edit', compact('bidangIndustri'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all()),
                'msgField' => $validator->errors()
            ], 422);
        }
        $bidangIndustri = BidangIndustri::findOrFail($id);
        $bidangIndustri->update($request->only('nama'));
        return response()->json([
            'message' => 'Berhasil mengubah bidang industri'
        ]);
    }

    public function destroy($id)
    {
        try {
            $bidangIndustri = BidangIndustri::findOrFail($id);
            $bidangIndustri->delete();
            return response()->json([
                'message' => 'Berhasil menghapus bidang industri'
            ]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'SQLSTATE[23000]: Integrity constraint violation') !== false) {
                return response()->json(['message' => 'Data sedang dipakai!'], 422);
            }
            throw $e;
        }
    }
}
