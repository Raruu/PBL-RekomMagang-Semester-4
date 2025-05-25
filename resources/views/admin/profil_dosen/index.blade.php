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
                        <a href="{{ url('/admin/pengguna/dosen/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Dosen
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="dosenTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIP/Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Program Studi</th>
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
    <div class="modal fade" id="viewDosenModal" tabindex="-1" aria-labelledby="viewDosenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDosenModalLabel">Detail Dosen</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewDosenModalBody">
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
    <div class="modal fade" id="editDosenModal" tabindex="-1" aria-labelledby="editDosenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDosenModalLabel">Edit Dosen</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editDosenModalBody">
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
        /* Modal backdrop blur effect */
        .modal-backdrop {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Center modal vertically */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        /* Action buttons styling */
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

        /* Profile image styles */
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
    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTable
            const table = $('#dosenTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/admin/pengguna/dosen') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'username', name: 'username' },
                    { data: 'nama', name: 'nama' },
                    { data: 'email', name: 'email' },
                    { data: 'program_studi', name: 'program_studi' },
                    { data: 'status', name: 'status' },
                    { data: 'aksi', name: 'aksi' }
                ],
                columnDefs: [
                    { targets: 1, className: 'text-start' },
                    { targets: [0, 5, 6], className: 'text-center' },
                ]
            });

            // Initialize modals
            const viewModal = new coreui.Modal(document.getElementById('viewDosenModal'));
            const editModal = new coreui.Modal(document.getElementById('editDosenModal'));

            // View button handler
            $(document).on('click', '.view-btn', function () {
                const url = $(this).data('url');

                // Reset modal content
                $('#viewDosenModalBody').html(`
                                                    <div class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                `);

                // Show modal
                viewModal.show();

                // Load content
                $.get(url)
                    .done(function (response) {
                        $('#viewDosenModalBody').html(response);
                    })
                    .fail(function () {
                        $('#viewDosenModalBody').html(`
                                                            <div class="alert alert-danger">
                                                                Gagal memuat data dosen. Silakan coba lagi.
                                                            </div>
                                                        `);
                    });
            });

            // Edit button handler
            $(document).on('click', '.edit-btn', function () {
                const url = $(this).data('url');

                // Reset modal content
                $('#editDosenModalBody').html(`
                                                    <div class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                `);

                // Show modal
                editModal.show();

                // Load form
                $.get(url)
                    .done(function (response) {
                        $('#editDosenModalBody').html(response);
                    })
                    .fail(function () {
                        $('#editDosenModalBody').html(`
                                                            <div class="alert alert-danger">
                                                                Gagal memuat form edit. Silakan coba lagi.
                                                            </div>
                                                        `);
                    });
            });

            // Form submission handler
            $(document).on('submit', '#formEditDosen', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');

                // Tampilkan loading
                form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                $('#editDosenModal').modal('hide');
                                table.ajax.reload(null, false);
                            });
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseJSON.system_message);
                        let errorMessage = 'Gagal menyimpan perubahan';

                        if (xhr.status === 422) {
                            // Validasi error
                            errorMessage += ':\n';
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                errorMessage += `- ${errors[field][0]}\n`;
                            }
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire('Error!', errorMessage, 'error');
                    },
                    complete: function () {
                        form.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Perubahan');
                    }
                });
            });

            // Delete button handler
            $(document).on('click', '.delete-btn', function () {
                const url = $(this).data('url');
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data dosen ${nama} akan dihapus permanen!`,
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
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Ubah Status Akun?',
                    text: `Anda yakin ingin mengubah status akun ${nama}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/pengguna/dosen/${userId}/toggle-status`,
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