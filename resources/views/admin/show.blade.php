@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Detail Admin</h5>
                        <a href="{{ url('/admin/pengguna/admin') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                @if ($admin->profilAdmin->foto_profil)
                                    <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}" alt="Foto Profil"
                                        class="img-thumbnail rounded-circle"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('imgs/profile_placeholder.jpg') }}" alt="Default Profile"
                                        class="img-thumbnail rounded-circle"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 30%;">Username</th>
                                        <td>{{ $admin->username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <td>{{ $admin->profilAdmin->nama ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $admin->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Telepon</th>
                                        <td>{{ $admin->profilAdmin->nomor_telepon ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                                {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Dibuat</th>
                                        <td>{{ $admin->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diupdate</th>
                                        <td>{{ $admin->updated_at->format('d F Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ url('/admin/pengguna/admin/' . $admin->user_id . '/edit') }}"
                                class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ url('/admin/pengguna/admin/' . $admin->user_id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Konfirmasi sebelum menghapus data
            document.querySelector('.delete-form').addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
                    this.submit();
                }
            });
        </script>
    @endpush
@endsection
