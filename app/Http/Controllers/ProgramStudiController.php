<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $programs = ProgramStudi::paginate(10);
        return view('admin.program_studi.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.program_studi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        ProgramStudi::create($request->all());
        return redirect()->route('program_studi.index')->with('success', 'Program Studi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $program = ProgramStudi::findOrFail($id);
        return view('admin.program_studi.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_program' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $program = ProgramStudi::findOrFail($id);
        $program->update($request->all());
        return redirect()->route('program_studi.index')->with('success', 'Program Studi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $program = ProgramStudi::findOrFail($id);
        $program->delete();
        return redirect()->route('program_studi.index')->with('success', 'Program Studi berhasil dihapus!');
    }
}
