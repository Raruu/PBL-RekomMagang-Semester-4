@extends('layouts.app')
@section('title', $page->title)
@section('content-top')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <h4>{{ $breadcrumb->title }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($breadcrumb->list as $item)
                            <li class="breadcrumb-item">{{ $item }}</li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">{{ $page->title }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="mahasiswaTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Program Studi</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .action-btn-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }

        .action-btn-group .btn {
            border-radius: 5px;
            width: 30px;
            height: 30px;
            padding: 0;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .action-btn-group .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .action-btn-group form {
            margin: 0;
            padding: 0;
            line-height: 0;
        }

        @media (max-width: 576px) {
            .action-btn-group {
                justify-content: center;
                width: 100%;
            }
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@endpush
@push('end')
    <script type="module">
        const run = () => {
            $(function () {
                $('#mahasiswaTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('/admin/pengguna/mahasiswa') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'nim', name: 'nim' },
                        { data: 'nama', name: 'nama' },
                        { data: 'email', name: 'email' },
                        { data: 'program_studi', name: 'program_studi' },
                        { data: 'semester', name: 'semester' },
                        { data: 'status', name: 'status' },
                        { data: 'aksi', name: 'aksi' }
                    ]
                });
                // View detail mahasiswa
                $(document).on('click', '.view-btn', function () {
                    const url = $(this).data('url');
                    window.location.href = url;
                });

                // Edit mahasiswa
                $(document).on('click', '.edit-btn', function () {
                    const url = $(this).data('url');
                    window.location.href = url;
                });

                // Konfirmasi sebelum menghapus
                $(document).on('click', '.delete-btn', function () {
                    const url = $(this).data('url');
                    const username = $(this).data('username');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Data mahasiswa ${username} akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (res) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: res.message || 'Data mahasiswa berhasil dihapus.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    $('#mahasiswaTable').DataTable().ajax.reload();
                                },
                                error: function (xhr) {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: xhr.responseJSON?.error || 'Terjadi kesalahan saat menghapus data.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        };

        // Toggle status mahasiswa
        $(document).on('click', '.toggle-status-btn', function () {
            const userId = $(this).data('user-id');
            const username = $(this).data('username');

            console.log('Toggle button clicked for user:', userId, username);

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
                    // Tambahkan logging untuk debug
                    console.log('Sending AJAX request to:', `/admin/pengguna/mahasiswa/${userId}/toggle-status`);

                    $.ajax({
                        url: `/admin/pengguna/mahasiswa/${userId}/toggle-status`,
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            console.log('Success response:', res);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#mahasiswaTable').DataTable().ajax.reload(null, false); // reload tanpa reset pagination
                        },
                        error: function (xhr) {
                            console.error('Error response:', xhr);
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