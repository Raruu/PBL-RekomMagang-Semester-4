<!-- Modal Detail Lowongan - Elegant Design -->
<div class="modal fade" id="modalDetailLowongan" tabindex="-1" aria-labelledby="modalDetailLowonganLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Simplified Header -->
            <div class="modal-header bg-body-tertiary border-bottom">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="fas fa-eye text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4 class="modal-title mb-0 fw-semibold">Detail Lowongan Magang</h4>
                        <small class="text-body-secondary">Informasi lengkap posisi magang</small>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Header Information -->
                <div class="bg-body p-4 border-bottom">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-start">
                                <div class="col-lg-8">
                                    <h2 class="mb-2 fw-bold text-body-emphasis" id="detail-judul-lowongan"></h2>
                                    <p class="text-body-secondary mb-3 fs-5" id="detail-posisi"></p>
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <div class="d-flex align-items-center text-body-secondary">
                                                <i class="fas fa-building me-2 text-primary"></i>
                                                <span id="detail-perusahaan"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center text-body-secondary">
                                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                                <span id="detail-lokasi"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded-3 p-4 mb-0">
                                            <div class="fw-semibold">Status Lowongan</div>
                                            <div class="fs-5 fw-bold mt-1" id="detail-status">
                                                <span class="badge bg-success">Aktif</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="p-4">
                    <!-- Quick Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-lg-3 gaji">
                            <div class="card border h-100 ">
                                <div class="card-body text-center p-3 hover-success">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-money-bill-wave fs-3"></i>
                                    </div>
                                    <h6 class="text-success mb-1 fw-semibold">Gaji</h6>
                                    <div class="fw-bold text-success" id="detail-gaji">Rp 8.000.000</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card border h-100">
                                <div class="card-body text-center p-3 hover-info">
                                    <div class="text-info mb-2">
                                        <i class="fas fa-users fs-3"></i>
                                    </div>
                                    <h6 class="text-info mb-1 fw-semibold">Kuota</h6>
                                    <div class="fw-bold text-info" id="detail-kuota">25 Orang</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card border h-100 ">
                                <div class="card-body text-center p-3 hover-warning">
                                    <div class="text-warning mb-2">
                                        <i class="fas fa-laptop fs-3"></i>
                                    </div>
                                    <h6 class="text-warning mb-1 fw-semibold">Tipe Kerja</h6>
                                    <div class="fw-bold text-warning" id="detail-tipe-kerja">
                                        <span class="badge bg-primary">Remote</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card border h-100 ">
                                <div class="card-body text-center p-3 hover-danger">
                                    <div class="text-danger mb-2">
                                        <i class="fas fa-calendar-times fs-3"></i>
                                    </div>
                                    <h6 class="text-danger mb-1 fw-semibold">Batas Daftar</h6>
                                    <div class="fw-bold text-danger" id="detail-batas">31/12/2024</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Sections -->
                    <div class="row g-4">
                        <!-- Deskripsi -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header border-1 pb-2 ">
                                    <h5 class="mb-2 fw-semibold text-body-emphasis">Deskripsi Lowongan</h5>
                                </div>
                                <div class="card-body pt-2">
                                    <div id="detail-deskripsi" class="text-body lh-lg">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Persyaratan -->
                        <div class="col-lg-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header border-1 pb-2">
                                    <h5 class="mb-2 fw-semibold text-body-emphasis">Persyaratan</h5>
                                </div>
                                <div class="card-body pt-2 mt-1">
                                    <div id="detail-persyaratan" class="persyaratan-content">
                                        <!-- Bagian detail persyaratan akan diisi JS -->
                                    </div>
                                    <div class="mt-3" id="dokumen-persyaratan-wrapper" style="display:none">
                                        <div class="mb-2">
                                            <strong>Dokumen Persyaratan:</strong>
                                        </div>
                                        <ul id="detail-dokumen-persyaratan" class="mb-0"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keahlian -->
                        <div class="col-lg-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header border-1 pb-2">
                                    <h5 class="mb-2 fw-semibold text-body-emphasis">Keahlian Dibutuhkan</h5>
                                </div>
                                <div class="card-body pt-2 mt-1">
                                    <div id="detail-keahlian" class="keahlian-content">
                                        <div class="keahlian-badges">
                                            <span class="badge bg-secondary me-2 mb-2 px-3 py-2">
                                                JavaScript <small class="ms-1">(menengah)</small>
                                            </span>
                                            <span class="badge bg-info me-2 mb-2 px-3 py-2">
                                                React <small class="ms-1">(pemula)</small>
                                            </span>
                                            <span class="badge bg-warning me-2 mb-2 px-3 py-2">
                                                Node.js <small class="ms-1">(mahir)</small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Simplified Footer -->
            <div class="modal-footer bg-body-tertiary border-top">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <small class="text-body-secondary">
                        Pastikan Anda membaca semua informasi dengan teliti
                    </small>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-coreui-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Base Modal Styling */
    .modal-xl {
        max-width: 1200px;
    }

    @media (max-width: 768px) {
        .modal-xl {
            max-width: 95%;
            margin: 1rem auto;
        }
    }

    .hover-success:hover {
        background-color: green !important;
        transition: all 0.3s ease;
    }

    .hover-success:hover * {
        color: white !important;
    }

    .hover-info:hover {
        background-color: #0c6cfc !important;
        transition: all 0.3s ease;
    }

    .hover-info:hover * {
        color: white !important;
    }

    .hover-warning:hover {
        background-color: #eead20 !important;
        transition: all 0.3s ease;
    }

    .hover-warning:hover * {
        color: white !important;
    }

    .hover-danger:hover {
        background-color: #e04048 !important;
        transition: all 0.3s ease;
    }

    .hover-danger:hover * {
        color: white !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    /* Badge hover override */
    .hover-warning:hover .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Text and Content */
    .text-justify {
        text-align: justify;
        line-height: 1.6;
    }

    /* Persyaratan Content Styling */
    .persyaratan-content ul {
        list-style: none;
        padding-left: 0;
    }

    .persyaratan-content li {
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--cui-border-color-translucent);
        position: relative;
        padding-left: 2.5rem;
    }

    .persyaratan-content li:before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: var(--cui-success);
        position: absolute;
        left: 0;
        top: 0.75rem;
    }

    .persyaratan-content li:last-child {
        border-bottom: none;
    }

    /* Keahlian Badges */
    .keahlian-content .badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        margin: 0.25rem;
        border-radius: 50px;
        font-weight: 500;
    }

    /* Card Hover Effects */
    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-1px);
        box-shadow: var(--cui-box-shadow-lg) !important;
    }

    /* CoreUI Theme Compatibility */
    [data-coreui-theme="dark"] .bg-body-tertiary {
        background-color: var(--cui-tertiary-bg) !important;
    }

    [data-coreui-theme="dark"] .text-body-secondary {
        color: var(--cui-secondary-color) !important;
    }

    [data-coreui-theme="dark"] .text-body-emphasis {
        color: var(--cui-emphasis-color) !important;
    }

    [data-coreui-theme="dark"] .card {
        background-color: var(--cui-card-bg);
        border-color: var(--cui-border-color);
    }

    [data-coreui-theme="dark"] .card-header {
        background-color: transparent !important;
        border-color: var(--cui-border-color);
    }

    [data-coreui-theme="dark"] .modal-content {
        background-color: var(--cui-body-bg);
        border-color: var(--cui-border-color);
    }

    [data-coreui-theme="dark"] .modal-header {
        background-color: var(--cui-tertiary-bg) !important;
        border-color: var(--cui-border-color);
    }

    [data-coreui-theme="dark"] .modal-footer {
        background-color: var(--cui-tertiary-bg) !important;
        border-color: var(--cui-border-color);
    }

    /* Modal Animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translate(0, -25px);
    }

    .modal.show .modal-dialog {
        transform: none;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
        .modal-body .p-4 {
            padding: 1.5rem !important;
        }

        .card-body.p-4 {
            padding: 1.5rem !important;
        }

        .card-body.p-3 {
            padding: 1rem !important;
        }

        .row.g-3 {
            --bs-gutter-x: 0.75rem;
            --bs-gutter-y: 0.75rem;
        }

        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }
    }

    /* Badge Colors for Theme Compatibility */
    [data-coreui-theme="dark"] .badge {
        --cui-badge-color: var(--cui-white);
    }

    /* Improved spacing and typography */
    .fw-semibold {
        font-weight: 600;
    }

    .modal-title {
        line-height: 1.3;
    }

    /* Clean borders */
    .border {
        border: 1px solid var(--cui-border-color) !important;
    }

    .border-bottom {
        border-bottom: 1px solid var(--cui-border-color) !important;
    }

    .border-top {
        border-top: 1px solid var(--cui-border-color) !important;
    }
</style>
@endpush