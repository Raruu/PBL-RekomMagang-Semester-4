@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h3 class="m-0 font-weight-bold">{{ $page->title }}</h3>
                        <a href="{{ url('/admin/pengguna/admin/create') }}" class="btn btn-primary btn-sm">
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

    <!-- Modal for Detail View -->
    <div class="modal fade" id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="viewAdminModalLabel">Detail Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewAdminModalBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Form -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editAdminModalBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .modal-backdrop {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }
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
        .profile-img-container {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        @media (max-width: 576px) {
            .action-btn-group {
                justify-content: center;
                width: 100%;
            }
        }
    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#adminTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/admin/pengguna/admin') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'nama', name: 'nama' },
                    { data: 'nomor_telepon', name: 'nomor_telepon', searchable: false },
                    { data: 'status', name: 'status', searchable: false },
                    { data: 'aksi', name: 'aksi', searchable: false }
                ],
                columnDefs: [
                    { targets: 4, className: 'text-start' },
                    { targets: [0, 5, 6], className: 'text-center' },
                ]
            });

            @if($search != null)
                setTimeout(() => {
                    table.search('{{ $search }}').draw();
                }, 1);
            @endif
            
            const viewModal = new coreui.Modal(document.getElementById('viewAdminModal'));
            const editModal = new coreui.Modal(document.getElementById('editAdminModal'));

            // View button handler
            $(document).on('click', '.view-btn', function () {
                const url = $(this).data('url');

                $('#viewAdminModalBody').html(`
                                        <div class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    `);
                viewModal.show();

                $.get(url)
                    .done(function (response) {
                        $('#viewAdminModalBody').html(response);
                    })
                    .fail(function () {
                        $('#viewAdminModalBody').html(`
                                                <div class="alert alert-danger">
                                                    Gagal memuat data admin. Silakan coba lagi.
                                                </div>
                                            `);
                    });
            });

            // Edit button handler
            $(document).on('click', '.edit-btn', function () {
                const url = $(this).data('url');

                $('#editAdminModalBody').html(`
                                        <div class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    `);
                editModal.show();

                $.get(url)
                    .done(function (response) {
                        $('#editAdminModalBody').html(response);
                    })
                    .fail(function () {
                        $('#editAdminModalBody').html(`
                                                <div class="alert alert-danger">
                                                    Gagal memuat form edit. Silakan coba lagi.
                                                </div>
                                            `);
                    });
            });

            // Form submission handler - Updated version
            $(document).on('submit', '#formEditAdmin', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');

                form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                const formData = new FormData(this);

                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('_method', 'PUT');

                console.log('Form data being sent:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log('Success response:', response);
                        if (response.status === 'success' && response.message) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                editModal.hide();
                                table.ajax.reload(null, false);
                            });
                        }
                    },
                    error: function (xhr) {
                        console.log('Error response:', xhr.responseJSON);
                        let errorMessage = 'Gagal menyimpan perubahan';

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = 'Validasi gagal:\n';
                            for (const field in errors) {
                                errorMessage += `- ${errors[field][0]}\n`;
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error'
                        });
                    },
                    complete: function () {
                        form.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Perubahan');
                    }
                });
            });

            // Delete button handler
            $(document).on('click', '.delete-btn', function () {
                const url = $(this).data('url');
                const username = $(this).data('username');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data admin ${username} akan dihapus permanen!`,
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
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message || 'Data berhasil dihapus',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.error || 'Gagal menghapus data',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Toggle status handler
            $(document).on('click', '.toggle-status-btn', function () {
                const userId = $(this).data('user-id');
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
                            url: `/admin/pengguna/admin/${userId}/toggle-status`,
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error || 'Terjadi kesalahan',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush