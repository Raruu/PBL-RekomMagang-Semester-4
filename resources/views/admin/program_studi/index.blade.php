<!-- filepath: c:\laragon\www\PBL\resources\views\admin\program_studi\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Program Studi</h1>
    <a href="{{ route('program_studi.create') }}" class="btn btn-primary mb-3">Tambah Program Studi</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Program</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $program)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $program->nama_program }}</td>
                    <td>{{ $program->deskripsi }}</td>
                    <td>
                        <a href="{{ route('program_studi.edit', $program->program_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('program_studi.destroy', $program->program_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $programs->links() }}
</div>
@endsection
