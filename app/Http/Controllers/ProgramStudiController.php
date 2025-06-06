<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramStudi::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_program', function ($row) {
                    return $row->nama_program;
                })
                ->addColumn('aksi', function ($row) {
                    return '
                    <button class="btn btn-warning btn-sm btn-edit" data-id="' . $row->program_id . '">Edit</button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->program_id . '" data-nama_program="' . $row->nama_program . '">Hapus</button>
                ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('admin.program_studi.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);
        ProgramStudi::create($request->all());
        return response()->json(['message' => 'Program Studi berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $program = ProgramStudi::findOrFail($id);
        return response()->json(['program' => $program]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);
        $program = ProgramStudi::findOrFail($id);
        $program->update($request->all());
        return response()->json(['message' => 'Program Studi berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        try {
            $program = ProgramStudi::findOrFail($id);
            $program->delete();
            return response()->json(['message' => 'Program Studi berhasil dihapus!']);
        } catch (QueryException $e) {
            if (strpos($e->getMessage(), 'SQLSTATE[23000]: Integrity constraint violation') !== false) {
                return response()->json(['message' => 'Data Program Studi sedang dipakai!'], 422);
            }
            throw $e;
        }
    }
}
