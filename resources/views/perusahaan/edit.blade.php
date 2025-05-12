@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">{{ isset($perusahaan) ? 'Edit' : 'Tambah' }} Perusahaan Mitra</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($perusahaan) ? route('perusahaan.update', $perusahaan->perusahaan_id) : route('perusahaan.store') }}">
            @csrf
            @if(isset($perusahaan)) @method('PUT') @endif

            <div class="mb-3">
                <label for="lokasi_id" class="form-label">Lokasi</label>
                <select name="lokasi_id" class="form-select" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->lokasi_id }}" {{ (isset($perusahaan) && $perusahaan->lokasi_id == $lokasi->lokasi_id) ? 'selected' : '' }}>
                            {{ $lokasi->alamat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" class="form-control" value="{{ $perusahaan->nama_perusahaan ?? old('nama_perusahaan') }}" required>
            </div>

            <div class="mb-3">
                <label for="bidang_industri" class="form-label">Bidang Industri</label>
                <input type="text" name="bidang_industri" class="form-control" value="{{ $perusahaan->bidang_industri ?? old('bidang_industri') }}">
            </div>

            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" name="website" class="form-control" value="{{ $perusahaan->website ?? old('website') }}">
            </div>

            <div class="mb-3">
                <label for="kontak_email" class="form-label">Email</label>
                <input type="email" name="kontak_email" class="form-control" value="{{ $perusahaan->kontak_email ?? old('kontak_email') }}">
            </div>

            <div class="mb-3">
                <label for="kontak_telepon" class="form-label">Telepon</label>
                <input type="text" name="kontak_telepon" class="form-control" value="{{ $perusahaan->kontak_telepon ?? old('kontak_telepon') }}">
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ (isset($perusahaan) && $perusahaan->is_active == 1) ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (isset($perusahaan) && $perusahaan->is_active == 0) ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($perusahaan) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</div>
@endsection
