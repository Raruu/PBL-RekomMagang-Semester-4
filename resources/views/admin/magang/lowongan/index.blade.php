@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <!-- Header Section - Now Sticky -->
        <div class="d-flex flex-column mb-3 sticky-top">
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

        <!-- Action Bar - Also Sticky -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
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
                        <div class="d-flex align-items-center text-body-secondary">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>Data diperbarui secara real-time</small>
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
                                    <th class="text-center" style="width: 80px;">Kuota</th>
                                    <th class="text-center" style="width: 120px;">Tipe Kerja</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
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
    <style>
        .content {
            width: 75%;
        }

        /* Sticky Header Styles */
        .sticky-top-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: var(--cui-body-bg);
            padding-top: 1rem;
            margin-top: -1rem;
        }

        .sticky-action-bar {
            position: sticky;
            top: 120px;
            /* Adjust based on header height */
            z-index: 1019;
            background-color: var(--cui-body-bg);
            padding: 1rem 0;
            margin: -1rem 0 1rem 0;
        }

        /* Add backdrop blur effect for sticky elements */
        .sticky-top-header::before,
        .sticky-action-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100vw;
            right: -100vw;
            bottom: 0;
            background-color: var(--cui-body-bg);
            backdrop-filter: blur(10px);
            z-index: -1;
            opacity: 0.95;
        }

        /* Action Buttons Styling - Dark Mode Compatible */
        .btn-action {
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Card Enhancements - Dark Mode Compatible */
        .card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--cui-border-color);
            background-color: var(--cui-card-bg);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            background-color: var(--cui-card-cap-bg);
            border-bottom: 1px solid var(--cui-border-color);
        }

        .card-body {
            padding: 1.5rem;
            background-color: var(--cui-card-bg);
        }

        /* Icon Wrapper - Dark Mode Compatible */
        .icon-wrapper {
            width: 50px;
            height: 50px;
            background-color: var(--cui-primary-bg-subtle);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table Container with Border */
        .table-container {
            border: 2px solid var(--cui-border-color);
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--cui-body-bg);
        }

        .table-card {
            background-color: var(--cui-card-bg);
        }

        .table-card .card-body {
            padding: 1.5rem;
        }

        /* Table Enhancements - Dark Mode Compatible */
        .table {
            margin-bottom: 0;
            background-color: var(--cui-body-bg);
            color: var(--cui-body-color);
        }

        .table-header th {
            background-color: var(--cui-tertiary-bg);
            color: var(--cui-body-color);
            border-bottom: 2px solid var(--cui-border-color);
            border-right: 1px solid var(--cui-border-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-header th:last-child {
            border-right: none;
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--cui-border-color-translucent);
            border-right: 1px solid var(--cui-border-color-translucent);
            background-color: var(--cui-body-bg);
            color: var(--cui-body-color);
        }

        .table tbody td:last-child {
            border-right: none;
        }

        .table tbody tr:hover {
            background-color: var(--cui-table-hover-bg) !important;
            transform: scale(1.001);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover td {
            background-color: var(--cui-table-hover-bg) !important;
        }

        /* Action Button Group - Dark Mode Compatible */
        .action-btn-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .action-btn-group .btn {
            border-radius: 8px;
            width: 35px;
            height: 35px;
            padding: 0;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .action-btn-group .btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Enhancements - Dark Mode Compatible */
        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid var(--cui-border-color);
            padding: 0.5rem 0;
            min-width: 200px;
            background-color: var(--cui-dropdown-bg);
            box-shadow: var(--cui-box-shadow);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            color: var(--cui-dropdown-link-color);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: var(--cui-dropdown-link-hover-bg);
            color: var(--cui-dropdown-link-hover-color);
            transform: translateX(5px);
        }

        .dropdown-item.active {
            background-color: var(--cui-primary);
            color: var(--cui-primary-text);
        }

        .dropdown-header {
            color: var(--cui-secondary);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dropdown-divider {
            border-color: var(--cui-border-color);
        }

        /* Text Colors - Dark Mode Compatible */
        .text-body-secondary {
            color: var(--cui-secondary) !important;
        }

        /* Badge - Dark Mode Compatible */
        .badge {
            border-radius: 20px;
        }

        /* DataTables Dark Mode Compatibility */
        .dataTables_wrapper {
            color: var(--cui-body-color);
        }

        /* DataTables Pagination Styling with Bottom Margin */
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 2rem !important;
            /* Add bottom margin */
            margin-top: 1rem !important;
        }

        .dataTables_wrapper .dataTables_info {
            margin-bottom: 2rem !important;
            /* Add bottom margin */
            color: var(--cui-secondary);
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--cui-body-color) !important;
            background-color: var(--cui-body-bg) !important;
            border: 1px solid var(--cui-border-color) !important;
            margin: 0 2px;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--cui-primary-bg-subtle) !important;
            color: var(--cui-primary) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: var(--cui-primary) !important;
            color: var(--cui-primary-text) !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(var(--cui-primary-rgb), 0.3);
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--cui-input-bg);
            color: var(--cui-input-color);
            border: 1px solid var(--cui-input-border-color);
        }

        /* Enhanced pagination wrapper */
        .dataTables_wrapper .dataTables_paginate {
            text-align: center;
            padding: 1rem 0 2rem 0 !important;
        }

        /* Loading States - Dark Mode Compatible */
        .dataTables_processing {
            background-color: var(--cui-modal-bg) !important;
            color: var(--cui-body-color) !important;
            border: 1px solid var(--cui-border-color) !important;
            border-radius: 8px;
            box-shadow: var(--cui-box-shadow);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .btn-action span {
                display: none;
            }

            .btn-action {
                padding: 10px 12px;
            }

            .table-container {
                border-radius: 8px;
            }

            /* Adjust sticky positioning for mobile */
            .sticky-action-bar {
                top: 100px;
            }
        }

        /* Specific Dark Mode Overrides */
        [data-coreui-theme="dark"] .table-container {
            border-color: var(--cui-border-color);
        }

        [data-coreui-theme="dark"] .table thead th {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--cui-body-color);
        }

        [data-coreui-theme="dark"] .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-coreui-theme="dark"] .table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-coreui-theme="dark"] .sticky-top-header::before,
        [data-coreui-theme="dark"] .sticky-action-bar::before {
            background-color: var(--cui-dark);
        }

        /* Light Mode Specific */
        [data-coreui-theme="light"] .table thead th {
            background-color: #f8f9fa;
        }

        [data-coreui-theme="light"] .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        [data-coreui-theme="light"] .table tbody tr:hover td {
            background-color: #f8f9fa !important;
        }

        [data-coreui-theme="light"] .sticky-top-header::before,
        [data-coreui-theme="light"] .sticky-action-bar::before {
            background-color: var(--cui-light);
        }

        /* Status Badge Styling - Dark Mode Compatible */
        .badge-status {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }

        /* Border styles for different screen sizes */
        @media (min-width: 992px) {
            .table-container {
                border-width: 3px;
            }
        }

        /* Animation compatibility */
        .animated {
            animation-duration: 0.3s;
            animation-fill-mode: both;
        }
    </style>
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
                order: [[1, 'asc']],
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