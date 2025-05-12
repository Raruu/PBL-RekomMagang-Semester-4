@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Tambah Perusahaan Mitra</div>
    <div class="card-body">
        <form method="POST" action="{{ route('perusahaan.store') }}">
            @csrf

            <div class="mb-3">
                <label for="lokasi_id" class="form-label">Lokasi</label>
                <select name="lokasi_id" class="form-select" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->lokasi_id }}">{{ $lokasi->alamat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Bidang Industri</label>
                <input type="text" name="bidang_industri" class="form-control">
            </div>

            <div class="mb-3">
                <label>Website</label>
                <input type="url" name="website" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="kontak_email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="kontak_telepon" class="form-control">
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
