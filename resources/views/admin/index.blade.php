@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">{{ $breadcrumb->list[0] }}</li>
    <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $page->title }}</h6>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Admin
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="adminTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nomor Telepon</th>
                                        <th>Foto Profil</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adminData as $admin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $admin->user->username }}</td>
                                            <td>{{ $admin->user->email }}</td>
                                            <td>{{ $admin->nama }}</td>
                                            <td>{{ $admin->nomor_telepon }}</td>
                                            <td><img src="{{ asset('storage/' . $admin->foto_profil) }}" alt="Foto Profil"
                                                    width="50" height="50"></td>
                                            <td>
                                                <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                                    {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.show', $admin->user->user_id) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.edit', $admin->user->user_id) }}"
                                                        class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                    <button type="button"
                                                        class="btn btn-{{ $admin->is_active ? 'secondary' : 'success' }} btn-sm toggle-status-btn"
                                                        data-id="{{ $admin->user->user_id }}" data-username="{{ $admin->username }}"
                                                        title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <i
                                                            class="fas fa-{{ $admin->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.destroy', $admin->user->user_id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        const run = () => {
            $(function () {
                $('#adminTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.index') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'username', name: 'username' },
                        { data: 'email', name: 'email' },
                        { data: 'nama', name: 'nama' },
                        { data: 'nomor_telepon', name: 'nomor_telepon' },
                        { data: 'foto_profil', name: 'foto_profil', orderable: false, searchable: false },
                        { data: 'status', name: 'status' },
                        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    }
                });

                // Konfirmasi sebelum menghapus
                $(document).on('submit', '.delete-form', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data admin ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        };

        $(document).on('click', '.toggle-status-btn', function () {
            const userId = $(this).data('id');
            const username = $(this).data('username');

            Swal.fire({
                title: 'Ubah Status Akun?',
                text: `Anda yakin ingin mengubah status akun ${username}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/${userId}/toggle-status`,
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#adminTable').DataTable().ajax.reload(null, false); // reload tanpa reset pagination
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: xhr.responseJSON?.error || 'Terjadi kesalahan.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endpush

@push('styles')
    <style>
        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@endpush