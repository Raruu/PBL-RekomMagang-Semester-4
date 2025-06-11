@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-admin">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-chalkboard-teacher text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Kelola semua data dosen dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> dosen
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ url('/admin/pengguna/dosen/create') }}"
                    class="btn btn-primary btn-action d-flex align-items-center" id="btn-create">
                    <i class="fas fa-plus me-2"></i>
                    <span>Tambah Dosen</span>
                </a>
                <button type="button" class="btn btn-success btn-action d-flex align-items-center" id="btn-refresh">
                    <i class="fas fa-sync-alt me-2"></i>
                    <span>Refresh</span>
                </button>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="btn-group" role="group">
                    <button type="button"
                        class="btn btn-outline-primary btn-action dropdown-toggle d-flex align-items-center justify-content-between"
                        data-coreui-toggle="dropdown" id="filterStatusBtn" style="min-width: 210px; background-color: #f4f6fb; color: #4f46e5; border: 1.5px solid #4f46e5; font-weight:600;">
                        <span id="filterStatusLabel" class="me-2" style="margin-bottom:2px;">Semua Status</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="filter-status">
                        <li><a class="dropdown-item d-flex align-items-center" data-status="">
                                <i class="fas fa-stream me-2 text-info"></i>Semua Status
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-status="active">
                                <i class="fas fa-check-circle me-2 text-success"></i>Aktif
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-status="inactive">
                                <i class="fas fa-times-circle me-2 text-danger"></i>Nonaktif
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-header border-bottom">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-table me-2 text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Daftar Dosen</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="dosenTable"
                            style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIP/Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th class="text-center" style="width: 150px;">Program Studi</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Detail View -->
    <div class="modal fade" id="viewDosenModal" tabindex="-1" aria-labelledby="viewDosenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="viewDosenModalLabel">Detail Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"
                        aria-label="Close"></button>
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
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editDosenModalLabel">Edit Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"
                        aria-label="Close"></button>
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
    @vite(['resources/css/admin/profil.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            let filter = urlParams.get('filter');
            let filterLabel = 'Filter Status';
            if (filter === 'active') filterLabel = 'Aktif';
            else if (filter === 'inactive') filterLabel = 'Nonaktif';
            else if (filter === '' || filter === null) filterLabel = 'Semua Status';
            $('#filterStatusLabel').text(filterLabel);
            if (filter) {
                $('#filter-status .dropdown-item').removeClass('active');
                $('#filter-status .dropdown-item[data-status="' + filter + '"]').addClass('active');
            } else {
                $('#filter-status .dropdown-item').removeClass('active');
                $('#filter-status .dropdown-item[data-status=""]').addClass('active');
            }

            const table = $('#dosenTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/admin/pengguna/dosen') }}",
                    data: function (d) {
                        const activeFilter = $('#filter-status .dropdown-item.active').data('status');
                        if (activeFilter !== undefined && activeFilter !== '') {
                            d.filter = activeFilter;
                        }
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'username',
                    name: 'username'
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
                    name: 'program_studi',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    searchable: false
                }
                ],
                columnDefs: [{
                    targets: [0, 4, 5, 6],
                    className: 'text-center'
                },
                {
                    targets: 1,
                    className: 'text-start'
                }
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    processing: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</div>',
                    search: "Search:",
                    infoEmpty: "Tidak ada data yang tersedia",
                    emptyTable: "Tidak ada data dosen yang tersedia",
                },
                drawCallback: function (settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function (index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
            });

            $('#btn-refresh').on('click', function () {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.html('<i class="fas fa-spinner fa-spin me-2"></i><span>Refreshing...</span>');
                $btn.prop('disabled', true);

                table.ajax.reload(function () {
                    setTimeout(() => {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }, 500);
                });
            });

            // Filter functionality
            $('#filter-status').on('click', '.dropdown-item', function (e) {
                e.preventDefault();
                const status = $(this).data('status');
                const url = new URL(window.location);
                let label = 'Filter Status';
                let btnColor = '#f4f6fb';
                let textColor = '#4f46e5';
                if (status === 'active') {
                    label = 'Aktif';
                    btnColor = '#e6f9f0';
                    textColor = '#28a745';
                } else if (status === 'inactive') {
                    label = 'Nonaktif';
                    btnColor = '#fff0f0';
                    textColor = '#dc3545';
                } else {
                    label = 'Semua Status';
                    btnColor = '#f4f6fb';
                    textColor = '#4f46e5';
                }
                $('#filterStatusLabel').text(label);
                $('#filterStatusBtn').css({
                    'background-color': btnColor,
                    'color': textColor,
                    'border-color': textColor
                });
                $('#filter-status .dropdown-item').removeClass('active');
                $(this).addClass('active');
                if (status && status !== '') {
                    url.searchParams.set('filter', status);
                } else {
                    url.searchParams.delete('filter');
                }
                window.history.replaceState({}, '', url);
                table.ajax.reload();
            });

            const viewModal = new coreui.Modal(document.getElementById('viewDosenModal'));
            const editModal = new coreui.Modal(document.getElementById('editDosenModal'));

            $(document).on('click', '.view-btn', function () {
                const url = $(this).data('url');

                $('#viewDosenModalBody').html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `);
                viewModal.show();

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

            $(document).on('click', '.edit-btn', function () {
                const url = $(this).data('url');

                $('#editDosenModalBody').html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `);
                editModal.show();

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

            $(document).on('submit', '#formEditDosen', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');

                form.find('button[type="submit"]').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                const formData = new FormData(this);
                for (const pair of formData.entries()) {
                    if (typeof pair[1] === 'string')
                        formData.set(pair[0], sanitizeString(pair[1]));
                }

                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
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
                        form.find('button[type="submit"]').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan Perubahan');
                    }
                });
            });

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
                        swalLoading('Mengirim data ke server...');
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
                        swalLoading('Mengirim data ke server...');
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
                                    text: xhr.responseJSON?.error ||
                                        'Terjadi kesalahan',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            let btnColor = '#f4f6fb';
            let textColor = '#4f46e5';
            if (filter === 'active') {
                btnColor = '#e6f9f0';
                textColor = '#28a745';
            } else if (filter === 'inactive') {
                btnColor = '#fff0f0';
                textColor = '#dc3545';
            }
            $('#filterStatusBtn').css({
                'background-color': btnColor,
                'color': textColor,
                'border-color': textColor
            });
        });
    </script>
@endpush