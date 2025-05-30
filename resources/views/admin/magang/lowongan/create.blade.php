@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <!-- Header Section - Sticky -->
        <div class="d-flex flex-column mb-4 sticky-top">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-plus-circle text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Tambahkan lowongan magang baru untuk perusahaan mitra
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-clipboard-list me-1"></i>
                                Form Baru
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="d-flex flex-column" id="mainContent">
            <form id="formLowongan" action="{{ route('admin.magang.lowongan.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Kolom Kiri: Pilih Perusahaan + Info -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="d-flex flex-column gap-4">
                            <!-- Pilih Perusahaan -->
                            <div class="card shadow-sm">
                                <div class="card-header border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building me-2 text-primary"></i>
                                        <h5 class="mb-0 fw-semibold">Pilih Perusahaan Mitra</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-search me-1"></i>Perusahaan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="perusahaan_id" id="perusahaan_id"
                                            class="form-control form-select-enhanced" required>
                                            <option value="">-- Pilih Perusahaan --</option>
                                            @foreach ($perusahaanList as $perusahaan)
                                                @php
                                                    $bidang = $bidangList->firstWhere('bidang_id', $perusahaan->bidang_id);
                                                @endphp
                                                <option value="{{ $perusahaan->perusahaan_id }}"
                                                    data-nama="{{ $perusahaan->nama_perusahaan }}"
                                                    data-lokasi-id="{{ $perusahaan->lokasi->lokasi_id }}"
                                                    data-lokasi-alamat="{{ $perusahaan->lokasi->alamat }}"
                                                    data-bidang-nama="{{ $bidang->nama ?? 'N/A' }}">
                                                    {{ $perusahaan->nama_perusahaan }} - {{ $bidang->nama ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="lokasi_id" id="lokasi_id">
                                    </div>
                                </div>
                            </div>

                            <!-- Info Perusahaan -->
                            <div class="card shadow-sm d-none" id="infoPerusahaan">
                                <div class="card-header border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2 text-info"></i>
                                        <h5 class="mb-0 fw-semibold">Informasi Perusahaan</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-id-card me-2 text-primary"></i>
                                                <label class="form-label mb-0 fw-bold">Nama Perusahaan</label>
                                            </div>
                                            <div class="info-content border rounded p-3 bg-light-subtle" id="infoNama">
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-industry me-2 text-warning"></i>
                                                <label class="form-label mb-0 fw-bold">Bidang Industri</label>
                                            </div>
                                            <div class="info-content border rounded p-3 bg-light-subtle" id="infoIndustri">
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                                <label class="form-label mb-0 fw-bold">Alamat</label>
                                            </div>
                                            <div class="info-content border rounded p-3 bg-light-subtle" id="infoAlamat">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Form Data Lowongan -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-edit me-2 text-success"></i>
                                        <h5 class="mb-0 fw-semibold">Detail Lowongan</h5>
                                    </div>
                                    <div class="d-flex align-items-center text-body-secondary">
                                        <i class="fas fa-asterisk me-1" style="font-size: 8px;"></i>
                                        <small>Field bertanda * wajib diisi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Row 1: Judul & Posisi -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="judul_lowongan">
                                                <i class="fas fa-heading me-1 text-primary"></i>Judul Lowongan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="judul_lowongan" id="judul_lowongan"
                                                class="form-control form-input-enhanced" required
                                                placeholder="Masukkan judul lowongan...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="judul_posisi">
                                                <i class="fas fa-user-tie me-1 text-info"></i>Judul Posisi
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="judul_posisi" id="judul_posisi"
                                                class="form-control form-input-enhanced" required
                                                placeholder="Masukkan judul posisi...">
                                        </div>
                                    </div>

                                    <!-- Row 2: Deskripsi -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="deskripsi">
                                                <i class="fas fa-align-left me-1 text-secondary"></i>Deskripsi
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="deskripsi" id="deskripsi"
                                                class="form-control form-textarea-enhanced" rows="4" required
                                                placeholder="Deskripsikan detail lowongan, tugas, dan tanggung jawab..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Row 3: Gaji & Kuota -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="gaji">
                                                <i class="fas fa-money-bill-wave me-1 text-success"></i>Gaji
                                                <small class="text-muted">(opsional)</small>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="gaji" id="gaji"
                                                    class="form-control form-input-enhanced" placeholder="0" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="kuota">
                                                <i class="fas fa-users me-1 text-warning"></i>Kuota
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="kuota" id="kuota"
                                                class="form-control form-input-enhanced" required min="1"
                                                placeholder="Jumlah peserta">
                                        </div>
                                    </div>

                                    <!-- Row 4: Tipe Kerja & Batas Pendaftaran -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="tipe_kerja_lowongan">
                                                <i class="fas fa-laptop me-1 text-info"></i>Tipe Kerja
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="tipe_kerja_lowongan" id="tipe_kerja_lowongan"
                                                class="form-control form-select-enhanced" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="remote">🏠 Remote</option>
                                                <option value="onsite">🏢 Onsite</option>
                                                <option value="hybrid">⚡ Hybrid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="batas_pendaftaran">
                                                <i class="fas fa-calendar-alt me-1 text-danger"></i>Batas Pendaftaran
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="batas_pendaftaran" id="batas_pendaftaran"
                                                class="form-control form-input-enhanced" required>
                                        </div>
                                    </div>

                                    <!-- Row 5: Status -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="is_active" id="is_active"
                                                    class="form-check-input" value="1" checked>
                                                <label class="form-check-label fw-bold" for="is_active">
                                                    <i class="fas fa-toggle-on me-1 text-success"></i>Aktifkan Lowongan
                                                </label>
                                                <div class="form-text">Lowongan akan langsung dapat dilihat oleh mahasiswa
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sticky Footer Navigation -->
        <div class="sticky-footer-nav">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <!-- Left Side - Back Button -->
                    <div class="footer-nav-left">
                        <a href="{{ route('admin.magang.lowongan.index') }}"
                            class="btn btn-footer btn-secondary d-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            <span class="d-none d-sm-inline">Kembali</span>
                        </a>
                    </div>

                    <!-- Center - Page Title & Reset -->
                    <div class="footer-nav-center d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-footer btn-outline-warning d-flex align-items-center"
                            id="btn-reset">
                            <i class="fas fa-undo me-2"></i>
                            <span class="d-none d-md-inline">Reset Form</span>
                        </button>

                        <div class="footer-title d-none d-lg-block">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plus-circle me-2 text-primary"></i>
                                <span class="fw-bold text-body">{{ $page->title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Save & Continue -->
                    <div class="footer-nav-right">
                        <button type="button" class="btn btn-footer btn-success d-flex align-items-center"
                            id="btn-save-continue">
                            <i class="fas fa-save me-2"></i>
                            <span class="d-none d-sm-inline">Simpan & Lanjutkan</span>
                        </button>
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

        #mainContent {
            background-color: var(--cui-body-bg);
            padding: 20px;
            padding-bottom: 80px;
            /* Space for sticky footer */
        }

        /* Sticky Footer Navigation */
        .sticky-footer-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: var(--cui-body-bg);
            border-top: 2px solid var(--cui-border-color);
            backdrop-filter: blur(15px);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
        }

        .sticky-footer-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--cui-body-bg);
            opacity: 0.75;
            z-index: -1;
        }

        /* Footer Navigation Buttons */
        .btn-footer {
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            min-height: 48px;
        }

        .btn-footer:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-footer.btn-secondary {
            background: var(--cui-secondary);
            color: white;
            border-color: var(--cui-secondary);
        }

        .btn-footer.btn-secondary:hover {
            background: var(--cui-secondary-dark);
            border-color: var(--cui-secondary-dark);
        }

        .btn-footer.btn-outline-warning {
            color: var(--cui-warning);
            border-color: var(--cui-warning);
            background: transparent;
        }

        .btn-footer.btn-outline-warning:hover {
            background: var(--cui-warning);
            color: var(--cui-dark);
            border-color: var(--cui-warning);
        }

        .btn-footer.btn-success {
            background: var(--cui-success);
            color: white;
            border-color: var(--cui-success);
        }

        .btn-footer.btn-success:hover {
            background: var(--cui-success-dark);
            border-color: var(--cui-success-dark);
        }

        /* Footer Title */
        .footer-title {
            color: var(--cui-body-color);
            font-size: 1.1rem;
            white-space: nowrap;
        }

        /* Footer Layout */
        .footer-nav-left,
        .footer-nav-right {
            flex: 0 0 auto;
        }

        .footer-nav-center {
            flex: 1;
            justify-content: center;
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

        /* Enhanced Form Styling */
        .form-input-enhanced,
        .form-select-enhanced,
        .form-textarea-enhanced {
            border-radius: 8px;
            border: 2px solid var(--cui-border-color);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background-color: var(--cui-input-bg);
            color: var(--cui-input-color);
        }

        .form-input-enhanced:focus,
        .form-select-enhanced:focus,
        .form-textarea-enhanced:focus {
            border-color: var(--cui-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--cui-primary-rgb), 0.25);
            transform: translateY(-1px);
        }

        /* Info Content Styling */
        .info-content {
            background-color: var(--cui-tertiary-bg) !important;
            color: var(--cui-body-color);
            border-color: var(--cui-border-color) !important;
            min-height: 3rem;
            display: flex;
            align-items: center;
        }

        .info-item {
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(5px);
        }

        /* Form Labels */
        .form-label {
            color: var(--cui-body-color);
            margin-bottom: 0.5rem;
        }

        /* Input Group Styling */
        .input-group-text {
            background-color: var(--cui-tertiary-bg);
            border-color: var(--cui-border-color);
            color: var(--cui-body-color);
            border-radius: 8px 0 0 8px;
        }

        /* Form Switch Styling */
        .form-check-input:checked {
            background-color: var(--cui-success);
            border-color: var(--cui-success);
        }

        /* Text Colors - Dark Mode Compatible */
        .text-body-secondary {
            color: var(--cui-secondary) !important;
        }

        .bg-light-subtle {
            background-color: var(--cui-tertiary-bg) !important;
        }

        /* Badge - Dark Mode Compatible */
        .badge {
            border-radius: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .btn-footer {
                padding: 10px 16px;
                min-height: 44px;
            }

            .footer-nav-center .footer-title {
                display: none !important;
            }

            body {
                padding-bottom: 70px;
            }
        }

        @media (max-width: 576px) {
            .btn-footer {
                padding: 8px 12px;
                min-height: 40px;
            }

            .footer-nav-center {
                flex-direction: column;
                gap: 8px !important;
            }

            body {
                padding-bottom: 75px;
            }
        }

        /* Specific Dark Mode Overrides */
        [data-coreui-theme="dark"] .sticky-top-header::before {
            background-color: var(--cui-dark);
        }

        [data-coreui-theme="dark"] .sticky-footer-nav::before {
            background-color: var(--cui-dark);
        }

        [data-coreui-theme="dark"] .info-content {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Light Mode Specific */
        [data-coreui-theme="light"] .sticky-top-header::before {
            background-color: var(--cui-light);
        }

        [data-coreui-theme="light"] .sticky-footer-nav::before {
            background-color: var(--cui-light);
        }

        [data-coreui-theme="light"] .info-content {
            background-color: #f8f9fa !important;
        }

        /* Animation */
        .animated {
            animation-duration: 0.3s;
            animation-fill-mode: both;
        }

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

        .fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Form Validation States */
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--cui-danger);
        }

        .form-control.is-valid,
        .form-select.is-valid {
            border-color: var(--cui-success);
        }

        /* Footer Button Active States */
        .btn-footer:active {
            transform: translateY(0);
        }

        .btn-footer:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(var(--cui-primary-rgb), 0.25);
        }

        /* Footer Nav Hover Effects */
        .footer-nav-left:hover .btn-footer,
        .footer-nav-right:hover .btn-footer {
            transform: translateY(-1px) scale(1.02);
        }
    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formLowongan');
            const perusahaanSelect = document.getElementById('perusahaan_id');
            const lokasiInput = document.getElementById('lokasi_id');
            const infoContainer = document.getElementById('infoPerusahaan');
            const infoNama = document.getElementById('infoNama');
            const infoIndustri = document.getElementById('infoIndustri');
            const infoAlamat = document.getElementById('infoAlamat');

            // Perusahaan selection handler
            perusahaanSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    infoNama.textContent = selected.dataset.nama;
                    infoIndustri.textContent = selected.dataset.bidangNama;
                    infoAlamat.textContent = selected.dataset.lokasiAlamat;
                    lokasiInput.value = selected.dataset.lokasiId;

                    infoContainer.classList.remove('d-none');
                    infoContainer.classList.add('fade-in-up');
                } else {
                    lokasiInput.value = '';
                    infoContainer.classList.add('d-none');
                    infoContainer.classList.remove('fade-in-up');
                    infoNama.textContent = '';
                    infoIndustri.textContent = '';
                    infoAlamat.textContent = '';
                }
            });

            // Reset form button
            document.getElementById('btn-reset').addEventListener('click', function () {
                Swal.fire({
                    title: 'Reset Form?',
                    text: 'Semua data yang telah diisi akan dihapus',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                        infoContainer.classList.add('d-none');
                        lokasiInput.value = '';

                        // Remove validation classes
                        const formInputs = form.querySelectorAll('input, select, textarea');
                        formInputs.forEach(input => {
                            input.classList.remove('is-invalid', 'is-valid');
                        });

                        Swal.fire({
                            title: 'Form direset!',
                            text: 'Semua field telah dikosongkan',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                return isValid;
            }

            // Enhanced form submission handler
            function submitForm(continueAfter = false) {
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Valid',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                    });
                    return;
                }

                const formData = new FormData(form);
                const submitBtn = document.querySelector('button[type="submit"]');
                const originalHtml = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span>Menyimpan...</span>';
                submitBtn.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            let errorMessages = '';
                            for (const key in errorData.errors) {
                                errorMessages += `${errorData.errors[key].join(', ')}\n`;
                            }
                            throw new Error(errorMessages);
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (continueAfter) {
                                window.location.href = `/admin/magang/lowongan/${data.lowongan_id}/lanjutan`;
                            } else {
                                window.location.href = '{{ route("admin.magang.lowongan.index") }}';
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: error.message,
                        });
                    })
                    .finally(() => {
                        // Restore button state
                        submitBtn.innerHTML = originalHtml;
                        submitBtn.disabled = false;
                    });
            }

            // Form submit handler
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm(false);
            });

            // Save and continue button
            document.getElementById('btn-save-continue').addEventListener('click', function (e) {
                e.preventDefault();
                submitForm(true);
            });

            // Real-time validation
            const formInputs = form.querySelectorAll('input, select, textarea');
            formInputs.forEach(input => {
                input.addEventListener('blur', function () {
                    if (this.hasAttribute('required')) {
                        if (!this.value.trim()) {
                            this.classList.add('is-invalid');
                            this.classList.remove('is-valid');
                        } else {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        }
                    }
                });

                input.addEventListener('input', function () {
                    if (this.classList.contains('is-invalid') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });

            // Set minimum date for batas_pendaftaran to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('batas_pendaftaran').setAttribute('min', today);
        });
    </script>
@endpush