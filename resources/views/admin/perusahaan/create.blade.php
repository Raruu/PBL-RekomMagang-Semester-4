@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Tambah Perusahaan Baru</h5>
                        <a href="javascript:window.history.back()" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ url('/admin/perusahaan/') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                         <label for="lokasi_id" class="form-label">Lokasi <span
                                            class="text-danger">*</span></label>
                                            <select name="lokasi_id" class="form-select" required>
                                                <option value="">-- Pilih Lokasi --</option>
                                                @foreach($lokasis as $lokasi)
                                                    <option value="{{ $lokasi->lokasi_id }}">{{ $lokasi->alamat }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span
                                            class="text-danger">*</span></label>
                                    <input type="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                        id="nama_perusahaan" name="nama_perusahaan">
                                    @error('nama_perusahaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bidang_industri" class="form-label">Bidang Industri <span
                                                class="text-danger">*</span></label>
                                        <input type="bidang_industri" class="form-control @error('bidang_industri') is-invalid @enderror"
                                            id="bidang_industri" name="bidang_industri" required>
                                        @error('bidang_industri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website <span
                                                class="text-danger">*</span></label>
                                        <input type="website" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" required>
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kontak_email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="kontak_email" class="form-control @error('kontak_email') is-invalid @enderror"
                                            id="kontak_email" name="kontak_email" required>
                                        @error('kontak_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kontak_telepon" class="form-label">Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="kontak_telepon" class="form-control @error('kontak_telepon') is-invalid @enderror"
                                            id="kontak_telepon" name="kontak_telepon" required>
                                        @error('kontak_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
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
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(form);

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
                                // Tampilkan validasi error per input jika mau
                                let msg = Object.values(errorData.errors).map(e => e.join('<br>')).join('<br>');
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
                                window.location.href = "{{ url('/admin/perusahaan') }}"; // redirect setelah sukses
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