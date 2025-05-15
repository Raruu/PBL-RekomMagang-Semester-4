<!-- resources/views/admin/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Edit Perusahaan</h5>
                        <a href="javascript:window.history.back()" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ url('/admin/perusahaan/' . $perusahaan->perusahaan_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lokasi_id" class="form-label">Lokasi <span
                                        class="text-danger">*</span></label>
                                        <select name="lokasi_id" class="form-select" required>
                                            <option value="">-- Pilih Lokasi --</option>
                                            @foreach($lokasis as $lokasi)
                                                <option value="{{ $lokasi->lokasi_id }}" {{ (isset($perusahaan) && $perusahaan->lokasi_id == $lokasi->lokasi_id) ? 'selected' : '' }}>
                                                    {{ $lokasi->alamat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span
                                                class="text-danger">*</span></label>
                                        <input type="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                            id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}"
                                            required>
                                        @error('nama_perusahaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bidang_industri" class="form-label">Bidang Industri <span
                                                class="text-danger">*</span></label>
                                        <input type="bidang_industri" class="form-control @error('bidang_industri') is-invalid @enderror"
                                            id="bidang_industri" name="bidang_industri" value="{{ old('bidang_industri', $perusahaan->bidang_industri) }}"
                                            required>
                                        @error('bidang_industri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website <span
                                                class="text-danger">*</span></label>
                                        <input type="website" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" value="{{ old('website', $perusahaan->website) }}"
                                            required>
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kontak_email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="kontak_email" class="form-control @error('kontak_email') is-invalid @enderror"
                                            id="kontak_email" name="kontak_email" value="{{ old('kontak_email', $perusahaan->kontak_email) }}"
                                            required>
                                        @error('kontak_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kontak_telepon" class="form-label">Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="kontak_telepon" class="form-control @error('kontak_telepon') is-invalid @enderror"
                                            id="kontak_telepon" name="kontak_telepon" value="{{ old('kontak_telepon', $perusahaan->kontak_telepon) }}"
                                            required>
                                        @error('kontak_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select name="is_active" class="form-select" required>
                                            <option value="1" {{ (isset($perusahaan) && $perusahaan->is_active == 1) ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ (isset($perusahaan) && $perusahaan->is_active == 0) ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
