@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
    <div class="container-fluid px-4">
        <!-- Header Section - Sticky -->
        <div class="d-flex flex-column mb-4 header-create-lowongan">
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
                            <button type="button" class="btn btn-footer btn-outline-warning d-flex align-items-center"
                                id="btn-reset">
                                <i class="fas fa-undo me-2"></i>
                                <span class="d-none d-md-inline">Reset Form</span>
                            </button>
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
                                                    $bidang = $bidangList->firstWhere(
                                                        'bidang_id',
                                                        $perusahaan->bidang_id,
                                                    );
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
                                    <div class="d-flex align-items-center text-danger">
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
                                                Judul Lowongan
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
                                                Judul Posisi
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
                                                Deskripsi
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="deskripsi" id="deskripsi" class="form-control form-textarea-enhanced" rows="4" required
                                                placeholder="Deskripsikan detail lowongan, tugas, dan tanggung jawab..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Row 3: Gaji & Kuota -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="gaji">
                                                Gaji
                                                <small class="text-muted">(opsional)</small>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="gaji" id="gaji"
                                                    class="form-control form-input-enhanced" placeholder="0"
                                                    min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="kuota">
                                                Kuota
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
                                                Tipe Kerja
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="tipe_kerja_lowongan" id="tipe_kerja_lowongan"
                                                class="form-control form-select-enhanced" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="remote">🌐 Remote</option>
                                                <option value="onsite">🏢 Onsite</option>
                                                <option value="hybrid">⚡ Hybrid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="batas_pendaftaran">
                                                Batas Pendaftaran
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
                                                    Aktifkan Lowongan
                                                </label>
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
        <div class="footer-create-lowongan">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <!-- Left Side - Back Button -->
                    <div class="footer-nav-left">
                        <a href="{{ route('admin.magang.lowongan.index') }}"
                            class="btn btn-footer kembali d-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            <span class="d-none d-sm-inline">Kembali</span>
                        </a>
                    </div>

                    <!-- Center - Page Title & Reset -->
                    <div class="footer-nav-center d-flex align-items-center gap-3">
                        <div class="footer-title d-none d-lg-block">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plus-circle me-2 text-primary"></i>
                                <span class="fw-bold text-body">{{ $page->title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Save & Continue -->
                    <div class="footer-nav-right">
                        <button type="button" class="btn btn-footer selanjutnya d-flex align-items-center"
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
    @vite(['resources/css/lowongan/create.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formLowongan');
            const perusahaanSelect = document.getElementById('perusahaan_id');
            const lokasiInput = document.getElementById('lokasi_id');
            const infoContainer = document.getElementById('infoPerusahaan');
            const infoNama = document.getElementById('infoNama');
            const infoIndustri = document.getElementById('infoIndustri');
            const infoAlamat = document.getElementById('infoAlamat');

            // Perusahaan selection handler
            perusahaanSelect.addEventListener('change', function() {
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
            document.getElementById('btn-reset').addEventListener('click', function() {
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

            // Enhanced form submission handler - DIPERBAIKI
            function submitForm(continueAfter = false, clickedButton = null) {
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Valid',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                    });
                    return;
                }

                const formData = new FormData(form);
                for (const pair of formData.entries()) {
                    formData.set(pair[0], sanitizeString(pair[1]));
                }

                // PERBAIKAN: Gunakan button yang diklik atau cari yang spesifik
                const submitBtn = clickedButton || document.getElementById('btn-save-continue');
                const originalHtml = submitBtn.innerHTML;

                // Show loading state
                const loadingHtml = continueAfter ?
                    '<i class="fas fa-spinner fa-spin me-2"></i><span>Menyimpan & Melanjutkan...</span>' :
                    '<i class="fas fa-spinner fa-spin me-2"></i><span>Menyimpan...</span>';

                submitBtn.innerHTML = loadingHtml;
                submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content') || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            let errorMessages = '';

                            // Handle validation errors
                            if (errorData.errors) {
                                for (const key in errorData.errors) {
                                    errorMessages += `${errorData.errors[key].join(', ')}\n`;
                                }
                            } else if (errorData.message) {
                                errorMessages = errorData.message;
                            } else {
                                errorMessages = 'Terjadi kesalahan saat menyimpan data';
                            }

                            throw new Error(errorMessages);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data); // Debug log

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Data berhasil disimpan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (continueAfter && data.lowongan_id) {
                                // PERBAIKAN: Pastikan URL benar
                                const continueUrl =
                                    `/admin/magang/lowongan/${data.lowongan_id}/lanjutan`;
                                console.log('Redirecting to:', continueUrl); // Debug log
                                window.location.href = continueUrl;
                            } else {
                                // Kembali ke index
                                window.location.href = '{{ route('admin.magang.lowongan.index') }}';
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Submit error:', error); // Debug log

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

            document.getElementById('btn-save-continue').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Save & Continue button clicked');
                submitForm(true, this);
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted normally');
                submitForm(false);
            });

            // Set minimum date for batas_pendaftaran to today
            const today = new Date().toISOString().split('T')[0];
            const batasPendaftaran = document.getElementById('batas_pendaftaran');
            if (batasPendaftaran) {
                batasPendaftaran.setAttribute('min', today);
            }
        });
    </script>
@endpush
