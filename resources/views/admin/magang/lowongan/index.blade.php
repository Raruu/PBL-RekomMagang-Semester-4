@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <!-- Header Section - Now Sticky -->
        <div class="d-flex flex-column mb-3 header-lowongan">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-briefcase text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Kelola semua lowongan magang dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> lowongan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <!-- Left Actions -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.magang.lowongan.create') }}"
                    class="btn btn-primary btn-action d-flex align-items-center" id="btn-create">
                    <i class="fas fa-plus me-2"></i>
                    <span>Tambah Lowongan</span>
                </a>
                <button type="button" class="btn btn-success btn-action d-flex align-items-center" id="btn-refresh">
                    <i class="fas fa-sync-alt me-2"></i>
                    <span>Refresh</span>
                </button>
            </div>

            <!-- Right Actions -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="btn-group" role="group">
                    <button type="button"
                        class="btn btn-outline-secondary btn-action dropdown-toggle d-flex align-items-center"
                        data-coreui-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filter Status</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <h6 class="dropdown-header"><i class="fas fa-filter me-1"></i>Filter berdasarkan Status</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item filter-status active d-flex align-items-center" href="#"
                                data-status="all">
                                <i class="fas fa-list me-2 text-info"></i>Semua Status
                            </a></li>
                        <li><a class="dropdown-item filter-status d-flex align-items-center" href="#" data-status="1">
                                <i class="fas fa-check-circle me-2 text-success"></i>Aktif
                            </a></li>
                        <li><a class="dropdown-item filter-status d-flex align-items-center" href="#" data-status="0">
                                <i class="fas fa-times-circle me-2 text-danger"></i>Nonaktif
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-header border-bottom">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-table me-2 text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Daftar Lowongan Magang</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="lowonganMagangTable"
                            style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Judul Lowongan</th>
                                    <th>Posisi</th>
                                    <th>Perusahaan</th>
                                    <th>Lokasi</th>
                                    <th class="text-center" style="width: 150px;">Tipe Kerja</th>
                                    <th class="text-center" style="width: 220px;">Batas Pendaftaran</th>
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
@endsection

@push('styles')
    @vite (['resources/css/lowongan/index.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#lowonganMagangTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.magang.lowongan.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'judul_lowongan', name: 'judul_lowongan', searchable: true },
                    { data: 'judul_posisi', name: 'judul_posisi', searchable: false },
                    { data: 'perusahaan', name: 'perusahaan', searchable: false },
                    { data: 'lokasi', name: 'lokasi', searchable: false },
                    { data: 'tipe_kerja_lowongan', name: 'tipe_kerja_lowongan', searchable: false },
                    { data: 'batas_pendaftaran', name: 'batas_pendaftaran', searchable: false },
                    { data: 'status', name: 'status', searchable: false },
                    { data: 'aksi', name: 'aksi', searchable: false }
                ],
                columnDefs: [
                    { targets: [0, 5, 6, 7, 8], className: 'text-center' },
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    processing: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</div>',
                    search: "Search:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    emptyTable: "Tidak ada data lowongan yang tersedia",
                    paginate: {
                        first: "first",
                        previous: "prev",
                        next: "next",
                        last: "last"
                    }
                },
                drawCallback: function (settings) {
                    $('#record-count').text(settings._iRecordsDisplay);

                    // Add animation to new rows
                    $(this.api().table().body()).find('tr').each(function (index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
                // Dark mode compatibility
                initComplete: function () {
                    // Apply theme-specific styling after table initialization
                    applyThemeStyles();
                }
            });

            // Function to apply theme-specific styles
            function applyThemeStyles() {
                const isDark = document.documentElement.getAttribute('data-coreui-theme') === 'dark';

                if (isDark) {
                    $('.dataTables_wrapper').addClass('dark-theme');
                } else {
                    $('.dataTables_wrapper').removeClass('dark-theme');
                }
            }

            // Listen for theme changes
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'data-coreui-theme') {
                        applyThemeStyles();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-coreui-theme']
            });

            // Refresh button with loading animation
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
            $('.filter-status').on('click', function (e) {
                e.preventDefault();

                $('.filter-status').removeClass('active');
                $(this).addClass('active');

                const status = $(this).data('status');
                // Add your filter logic here

                // Update button text
                const filterText = $(this).text();
                $('.dropdown-toggle span').text(filterText);
            });

            // Toggle Status Handler
            $(document).on('click', '.toggle-status-btn', function () {
                const lowonganId = $(this).data('lowongan-id');
                const judulLowongan = $(this).data('judul');

                Swal.fire({
                    title: 'Ubah Status Lowongan?',
                    text: `Anda yakin ingin mengubah status lowongan "${judulLowongan}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated fadeIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/admin/magang/lowongan') }}/${lowonganId}/toggle-status`,
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
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error || 'Terjadi kesalahan',
                                    icon: 'error',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                            }
                        });
                    }
                });
            });

            // Delete Handler
            $(document).on('click', '.delete-btn', function () {
                const url = $(this).data('url');
                const judulLowongan = $(this).data('judul');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data lowongan "${judulLowongan}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated fadeIn'
                    }
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
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
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
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
                    @keyframes fadeInUp {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .animated {
                        animation-duration: 0.3s;
                        animation-fill-mode: both;
                    }

                    .fadeIn {
                        animation-name: fadeIn;
                    }

                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                `;
        document.head.appendChild(style);
    </script>
@endpush