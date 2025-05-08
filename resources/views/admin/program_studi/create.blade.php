<!-- filepath: c:\laragon\www\PBL\resources\views\admin\program_studi\create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Program Studi</h1>
    <form action="{{ route('program_studi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_program" class="form-label">Nama Program</label>
            <input type="text" class="form-control" id="nama_program" name="nama_program" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
