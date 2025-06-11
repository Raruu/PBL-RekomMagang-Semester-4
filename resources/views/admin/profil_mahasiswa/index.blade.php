@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-mahasiswa">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-user-graduate text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Kelola semua akun mahasiswa dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> mahasiswa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
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
                
                <div class="btn-group" role="group">
                    <button type="button"
                        class="btn btn-outline-secondary btn-action dropdown-toggle d-flex align-items-center justify-content-between"
                        data-coreui-toggle="dropdown" id="filterVerifBtn" style="min-width: 250px; background-color: #f8f9fa; color: #6c757d; border: 1.5px solid #6c757d; font-weight:600;">
                        <span id="filterVerifLabel" class="me-2" style="margin-bottom:2px;">Semua Status Verifikasi</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="filter-verif">
                        <li><a class="dropdown-item d-flex align-items-center" data-verif="">
                                <i class="fas fa-stream me-2 text-info"></i>Semua Status Verifikasi
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-verif="verified">
                                <i class="fas fa-check-circle me-2 text-success"></i>Terverifikasi
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-verif="unverified">
                                <i class="fas fa-times-circle me-2 text-danger"></i>Belum Terverifikasi
                            </a></li>
                        <li><a class="dropdown-item d-flex align-items-center" data-verif="meminta_verif">
                                <i class="fas fa-clock me-2 text-warning"></i>Meminta Verifikasi
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-header border-bottom">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-table me-2 text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Daftar Mahasiswa</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="mahasiswaTable" style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Program Studi</th>
                                    <th class="text-center">Angkatan</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 150px;">Status Verifikasi</th>
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
    <div class="modal fade" id="viewMahasiswaModal" tabindex="-1" aria-labelledby="viewMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white sticky-top" style="z-index: 1055;">
                    <h5 class="modal-title" id="viewMahasiswaModalLabel">Detail Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
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
    <div class="modal fade" id="editMahasiswaModal" tabindex="-1" aria-labelledby="editMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editMahasiswaModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal" aria-label="Close"></button>
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
    @vite(['resources/css/admin/profil.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            let filter = urlParams.get('filter');
            let filterVerif = urlParams.get('filter_verif');
            
            let filterLabel = 'Semua Status';
            if (filter === 'active') filterLabel = 'Aktif';
            else if (filter === 'inactive') filterLabel = 'Nonaktif';
            $('#filterStatusLabel').text(filterLabel);
            
            let filterVerifLabel = 'Semua Status Verifikasi';
            if (filterVerif === 'verified') filterVerifLabel = 'Terverifikasi';
            else if (filterVerif === 'unverified') filterVerifLabel = 'Belum Terverifikasi';
            else if (filterVerif === 'meminta_verif') filterVerifLabel = 'Meminta Verifikasi';
            $('#filterVerifLabel').text(filterVerifLabel);

            if (filter) {
                $('#filter-status .dropdown-item').removeClass('active');
                $('#filter-status .dropdown-item[data-status="' + filter + '"]').addClass('active');
            } else {
                $('#filter-status .dropdown-item').removeClass('active');
                $('#filter-status .dropdown-item[data-status=""]').addClass('active');
            }
            
            if (filterVerif) {
                $('#filter-verif .dropdown-item').removeClass('active');
                $('#filter-verif .dropdown-item[data-verif="' + filterVerif + '"]').addClass('active');
            } else {
                $('#filter-verif .dropdown-item').removeClass('active');
                $('#filter-verif .dropdown-item[data-verif=""]').addClass('active');
            }

            const table = $('#mahasiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/admin/pengguna/mahasiswa') }}",
                    data: function(d) {
                        const activeFilter = $('#filter-status .dropdown-item.active').data('status');
                        const activeVerifFilter = $('#filter-verif .dropdown-item.active').data('verif');
                        
                        if (activeFilter !== undefined && activeFilter !== '') {
                            d.filter = activeFilter;
                        }
                        if (activeVerifFilter !== undefined && activeVerifFilter !== '') {
                            d.filter_verif = activeVerifFilter;
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
                        data: 'nim',
                        name: 'nim'
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
                        name: 'status',
                        searchable: false
                    },
                    {
                        data: 'status_verif',
                        name: 'status_verif',
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: [0, 1, 5, 6, 7, 8],
                    className: 'text-center'
                }],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    processing: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</div>',
                    search: "Search:",
                    infoEmpty: "Tidak ada data yang tersedia",
                    emptyTable: "Tidak ada data mahasiswa yang tersedia",
                },
                drawCallback: function(settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function(index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                }
            });

            // Refresh button functionality
            $('#btn-refresh').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.html('<i class="fas fa-spinner fa-spin me-2"></i><span>Refreshing...</span>');
                $btn.prop('disabled', true);

                table.ajax.reload(function() {
                    setTimeout(() => {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }, 500);
                });
            });

            $('#filter-status').on('click', '.dropdown-item', function(e) {
                e.preventDefault();
                const status = $(this).data('status');
                const url = new URL(window.location);
                let label = 'Semua Status';
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

                url.searchParams.delete('filter_verif');
                $('#filterVerifLabel').text('Semua Status Verifikasi');
                $('#filterVerifBtn').css({
                    'background-color': '#f8f9fa',
                    'color': '#6c757d',
                    'border-color': '#6c757d'
                });
                $('#filter-verif .dropdown-item').removeClass('active');
                $('#filter-verif .dropdown-item[data-verif=""]').addClass('active');

                window.history.replaceState({}, '', url);
                table.ajax.reload();
            });

            $('#filter-verif').on('click', '.dropdown-item', function(e) {
                e.preventDefault();
                const verif = $(this).data('verif');
                const url = new URL(window.location);
                let label = 'Semua Status Verifikasi';
                let btnColor = '#f8f9fa';
                let textColor = '#6c757d';
                
                if (verif === 'verified') {
                    label = 'Terverifikasi';
                    btnColor = '#e6f9f0';
                    textColor = '#28a745';
                } else if (verif === 'unverified') {
                    label = 'Belum Terverifikasi';
                    btnColor = '#fff0f0';
                    textColor = '#dc3545';
                } else if (verif === 'meminta_verif') {
                    label = 'Meminta Verifikasi';
                    btnColor = '#fff8e1';
                    textColor = '#ffc107';
                }
                
                $('#filterVerifLabel').text(label);
                $('#filterVerifBtn').css({
                    'background-color': btnColor,
                    'color': textColor,
                    'border-color': textColor
                });
                
                $('#filter-verif .dropdown-item').removeClass('active');
                $(this).addClass('active');
                
                if (verif && verif !== '') {
                    url.searchParams.set('filter_verif', verif);
                } else {
                    url.searchParams.delete('filter_verif');
                }

                // Reset status filter to default
                url.searchParams.delete('filter');
                $('#filterStatusLabel').text('Semua Status');
                $('#filterStatusBtn').css({
                    'background-color': '#f4f6fb',
                    'color': '#4f46e5',
                    'border-color': '#4f46e5'
                });
                $('#filter-status .dropdown-item').removeClass('active');
                $('#filter-status .dropdown-item[data-status=""]').addClass('active');

                window.history.replaceState({}, '', url);
                table.ajax.reload();
            });

            const viewModal = new coreui.Modal(document.getElementById('viewMahasiswaModal'));
            const editModal = new coreui.Modal(document.getElementById('editMahasiswaModal'));

            $(document).on('click', '.verify-btn', function() {
                const userId = $(this).data('id');
                const file = $(this).data('file');

                swalLoading();

                axios.get("{{ route('admin.mahasiswa.verify', ['id' => ':id']) }}"
                        .replace(':id',
                            userId))
                    .then(response => {
                        const data = Object.values(response.data)[0];
                        const dataHtml = document.createElement('div');
                        const isNotValid = response.data.isNotValid;
                        if (isNotValid) {
                            dataHtml.innerHTML += `<h5 class="text-danger">Data Tidak Valid</h5>`
                        } else {
                            dataHtml.innerHTML += `@include('admin.profil_mahasiswa.index-verify-table')`
                        }

                        Swal.fire({
                            title: 'Verifikasi Akun',
                            html: `Apakah Anda yakin ingin memverifikasi akun ini? <br/> ${dataHtml.outerHTML} <a href="${file}" class="fs-5 text-decoration-none" download>Download File Transkrip Nilai</a>`,
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
                                swalLoading('Mengirim data ke server...');
                                $.ajax({
                                    url: "{{ route('admin.mahasiswa.verify', ['id' => ':id']) }}"
                                        .replace(':id', userId),
                                    type: 'PATCH',
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message
                                        }).then(function() {
                                            table.ajax.reload(null,
                                                false);
                                        });
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseJSON);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: xhr.responseJSON
                                                .message
                                        });
                                    }
                                });
                            } else if (result.isDenied) {
                                swalLoading('Mengirim data ke server...');
                                $.ajax({
                                    url: "{{ route('admin.mahasiswa.verify.reject', ['id' => ':id']) }}"
                                        .replace(':id', userId),
                                    type: 'PATCH',
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message
                                        }).then(function() {
                                            table.ajax.reload(null,
                                                false);
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

            $(document).on('submit', '#formEditMahasiswa', function(e) {
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

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
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
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: url,
                            type: 'DELETE',
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
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: `/admin/pengguna/mahasiswa/${userId}/toggle-status`,
                            method: 'PATCH',
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
