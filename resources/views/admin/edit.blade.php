<!-- resources/views/admin/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Edit Admin</h5>
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

                        <form action="{{ url('/admin/pengguna/admin/' . $admin->user_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username', $admin->username) }}"
                                            required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $admin->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <small
                                                class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama"
                                            value="{{ old('nama', $admin->profilAdmin->nama ?? '') }}" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                        <input type="text"
                                            class="form-control @error('nomor_telepon') is-invalid @enderror"
                                            id="nomor_telepon" name="nomor_telepon"
                                            value="{{ old('nomor_telepon', $admin->profilAdmin->nomor_telepon ?? '') }}">
                                        @error('nomor_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foto_profil" class="form-label">Foto Profil</label>
                                        <input type="file"
                                            class="form-control @error('foto_profil') is-invalid @enderror" id="foto_profil"
                                            name="foto_profil">
                                        @error('foto_profil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Format: JPG, JPEG, PNG (Maks 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            @if ($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="mb-3 text-center">
                                            <p>Foto Profil Saat Ini:</p>
                                            <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}"
                                                alt="Foto Profil" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ $admin->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Status Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>

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

    @push('scripts')
        <script>
            // Preview foto profil yang diunggah
            document.getElementById('foto_profil').addEventListener('change', function(e) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const imgElement = document.querySelector('img.img-thumbnail');
                    if (imgElement) {
                        imgElement.src = event.target.result;
                    } else {
                        // Jika belum ada gambar, buat elemen gambar baru
                        const newImgContainer = document.createElement('div');
                        newImgContainer.className = 'row mb-3';
                        newImgContainer.innerHTML = `
                            <div class="col-md-12">
                                <div class="mb-3 text-center">
                                    <p>Foto Profil Baru:</p>
                                    <img src="${event.target.result}" alt="Foto Profil" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        `;
                        document.querySelector('.form-check').parentNode.parentNode.before(newImgContainer);
                    }
                }
                reader.readAsDataURL(this.files[0]);
            });
        </script>
    @endpush
@endsection
