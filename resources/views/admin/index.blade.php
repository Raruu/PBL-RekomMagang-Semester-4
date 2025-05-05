@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Daftar Akun Admin</h5>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            @php
                                $isNonaktif = Str::contains(session('success'), 'dinonaktifkan');
                            @endphp
                            <div class="alert alert-{{ $isNonaktif ? 'danger' : 'success' }} alert-dismissible fade show"
                                role="alert" id="status-alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $index => $admin)
                                        <tr>
                                            <td>{{ $admins->firstItem() + $index }}</td>
                                            <td>{{ $admin->username }}</td>
                                            <td>{{ $admin->profilAdmin->nama ?? 'N/A' }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->profilAdmin->nomor_telepon ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                                    {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.show', $admin->user_id) }}"
                                                        class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.edit', $admin->user_id) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.toggle-status', $admin->user_id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="btn btn-{{ $admin->is_active ? 'secondary' : 'success' }} btn-sm"
                                                            title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i
                                                                class="fas fa-{{ $admin->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.destroy', $admin->user_id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data admin.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $admins->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Hilangkan alert sukses setelah 3 detik
            setTimeout(() => {
                const alert = document.getElementById('status-alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    alert.style.display = 'none';
                }
            }, 3000);
            
            // Konfirmasi sebelum menghapus data
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
                        this.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection