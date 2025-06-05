@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h3 class="m-0 font-weight-bold">{{ $page->title }}</h3>
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
                                        <th>Angkatan</th>
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
    <div class="modal fade" id="viewMahasiswaModal" tabindex="-1" aria-labelledby="viewMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white sticky-top" style="z-index: 1055;">
                    <h5 class="modal-title" id="viewMahasiswaModalLabel">Detail Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewMahasiswaModalBody">
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
    <div class="modal fade" id="editMahasiswaModal" tabindex="-1" aria-labelledby="editMahasiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editMahasiswaModalBody">
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

        .info-row {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .info-value {
            color: #212529;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#mahasiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/admin/pengguna/mahasiswa') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        className: "text-center"
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'program_studi',
                        name: 'program_studi'
                    },
                    {
                        data: 'angkatan',
                        name: 'angkatan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    }
                ],
                columnDefs: [
                    // { targets: 1, className: 'text-start' },
                    {
                        targets: [0, 5, 6, 7],
                        className: 'text-center'
                    },
                ]
            });

            const viewModal = new coreui.Modal(document.getElementById('viewMahasiswaModal'));
            const editModal = new coreui.Modal(document.getElementById('editMahasiswaModal'));

            // View button handler
            $(document).on('click', '.verify-btn', function() {
                const userId = $(this).data('id');
                const file = $(this).data('file');

                Swal.fire({
                    title: 'Mohon Tunggu...',
                    text: 'Sedang mengambil data dari server.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.get("{{ route('admin.mahasiswa.verify', -1) }}"
                        .replace('-1',
                            userId))
                    .then(response => {
                        const data = Object.values(response.data)[0];
                        const dataHtml = document.createElement('div');
                        dataHtml.innerHTML += `
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Semester</th>
                                        <th scope="col">IPK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.slice(1).map((item, index) =>  `
                                                    <tr>
                                                        <td>${item.semester}</td>
                                                        <td>${item.ipk}</td>
                                                    </tr>
                                                `).join('')}
                                </tbody>
                            </table>
                        `

                        Swal.fire({
                            title: 'Verifikasi Akun',
                            html: `Apakah Anda yakin ingin memverifikasi akun ini? <br/> ${dataHtml.outerHTML} <a href="${file}" class="fs-5 text-decoration-none" download>Download File Verifikasi</a>`,
                            icon: 'warning',
                            showCancelButton: true,
                            showDenyButton: true,
                            confirmButtonColor: '#3085d6',
                            denyButtonColor: '#d33',
                            cancelButtonColor: '#f0ad4e',
                            confirmButtonText: 'Verifikasi',
                            denyButtonText: 'Tolak',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('admin.mahasiswa.verify', -1) }}"
                                        .replace('-1', userId),
                                    type: 'PATCH',
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message
                                        }).then(function() {
                                            table.ajax.reload(null, false);
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: xhr.responseJSON
                                                .message
                                        });
                                    }
                                });
                            } else if (result.isDenied) {
                                $.ajax({
                                    url: "{{ route('admin.mahasiswa.verify.reject', -1) }}"
                                        .replace('-1', userId),
                                    type: 'PATCH',
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message
                                        }).then(function() {
                                            table.ajax.reload(null, false);
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: xhr.responseJSON
                                                .message
                                        });
                                    }
                                });
                            }
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.response.data.message
                        });
                    });
            });

            $(document).on('click', '.view-btn', function() {
                const url = $(this).data('url');

                $('#viewMahasiswaModalBody').html(`
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        `);

                viewModal.show();

                $.get(url)
                    .done(function(response) {
                        $('#viewMahasiswaModalBody').html(response);
                    })
                    .fail(function() {
                        $('#viewMahasiswaModalBody').html(`
                                    <div class="alert alert-danger">
                                        Gagal memuat data mahasiswa. Silakan coba lagi.
                                    </div>
                                `);
                    });
            });

            // Edit button handler
            $(document).on('click', '.edit-btn', function() {
                const url = $(this).data('url');

                $('#editMahasiswaModalBody').html(`
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        `);

                editModal.show();

                $.get(url)
                    .done(function(response) {
                        $('#editMahasiswaModalBody').html(response);
                    })
                    .fail(function() {
                        $('#editMahasiswaModalBody').html(`
                                    <div class="alert alert-danger">
                                        Gagal memuat form edit. Silakan coba lagi.
                                    </div>
                                `);
                    });
            });

            // Form submission handler
            $(document).on('submit', '#formEditMahasiswa', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');

                form.find('button[type="submit"]').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
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
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        let errorMessage = 'Gagal menyimpan perubahan';

                        if (xhr.status === 422) {
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
                    complete: function() {
                        form.find('button[type="submit"]').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan Perubahan');
                    }
                });
            });

            // Delete button handler
            $(document).on('click', '.delete-btn', function() {
                const url = $(this).data('url');
                const nama = $(this).data('nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data mahasiswa ${nama} akan dihapus permanen!`,
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
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message ||
                                        'Data berhasil dihapus',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON?.error ||
                                    'Gagal menghapus data',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Toggle status handler
            $(document).on('click', '.toggle-status-btn', function() {
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
                            url: `/admin/pengguna/mahasiswa/${userId}/toggle-status`,
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error ||
                                        'Terjadi kesalahan',
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
