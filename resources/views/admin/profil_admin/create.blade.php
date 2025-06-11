@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1"><i class="fas fa-user-plus me-2"></i>Tambah Admin Baru</h2>
                    <p class="text-muted">Silakan lengkapi data berikut untuk menambahkan admin baru ke sistem.</p>
                </div>
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                        <span class="fw-semibold"><i class="fas fa-user-cog me-2"></i>Formulir Admin</span>
                        <a href="javascript:window.history.back()" class="btn btn-light btn-sm border-0 shadow-sm px-3 py-1"
                            style="transition:0.2s;">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body bg-light">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ url('/admin/pengguna/admin') }}" method="POST" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror" id="username"
                                                name="username" value="{{ old('username') }}" required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                                id="nama" name="nama" value="{{ old('nama') }}" required>
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text"
                                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                                id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}">
                                            @error('nomor_telepon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foto_profil" class="form-label">Foto Profil</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                                            <input type="file"
                                                class="form-control @error('foto_profil') is-invalid @enderror" id="foto_profil"
                                                name="foto_profil" accept="image/jpeg,image/png,image/jpg">
                                            @error('foto_profil')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Format: JPG, JPEG, PNG (Maks 2MB)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                for (const pair of formData.entries()) {
                    if (typeof pair[1] === 'string')
                        formData.set(pair[0], sanitizeString(pair[1]));
                }

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            if (errorData.errors) {
                                let msg = Object.values(errorData.errors).map(e => e.join('<br>'))
                                    .join('<br>');
                                Swal.fire('Gagal!', msg, 'error');
                            } else {
                                throw new Error(errorData.error || 'Terjadi kesalahan.');
                            }
                        } else {
                            const success = await response.json();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: success.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href =
                                    "{{ url('/admin/pengguna/admin') }}";
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire('Gagal!', err.message, 'error');
                    });
            });
        });
    </script>
@endpush
