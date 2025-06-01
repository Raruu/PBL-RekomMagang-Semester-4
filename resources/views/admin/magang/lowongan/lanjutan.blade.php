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
                                <i class="fas fa-cogs text-success fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0">Lengkapi persyaratan dan keahlian untuk lowongan:
                                    <strong>{{ $lowongan->judul_lowongan }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
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

        <!-- Info Lowongan yang sudah dibuat -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm bg-light-subtle">
                    <div class="card-header border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 text-info"></i>
                            <h5 class="mb-0 fw-semibold">Informasi Lowongan</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-primary">
                                        <i class="fas fa-briefcase me-1"></i>Judul Lowongan
                                    </label>
                                    <div class="info-value">{{ $lowongan->judul_lowongan }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-warning">
                                        <i class="fas fa-building me-1"></i>Perusahaan
                                    </label>
                                    <div class="info-value">{{ $lowongan->perusahaanMitra->nama_perusahaan ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-success">
                                        <i class="fas fa-user-tie me-1"></i>Posisi
                                    </label>
                                    <div class="info-value">{{ $lowongan->judul_posisi }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-danger">
                                        <i class="fas fa-users me-1"></i>Kuota
                                    </label>
                                    <div class="info-value">{{ $lowongan->kuota }} orang</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-secondary">
                                        <i class="fas fa-file-alt me-1"></i>Deskripsi Lowongan
                                    </label>
                                    <div class="info-value">
                                        {{ $lowongan->deskripsi ?? 'Tidak ada deskripsi tersedia' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="d-flex flex-column" id="mainContent">
            <form id="formLanjutan" action="{{ route('admin.magang.lowongan.lanjutan.store', $lowongan->lowongan_id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <!-- Kolom Kiri: Persyaratan Magang -->
                    <div class="col-xl-5 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clipboard-check me-2 text-primary"></i>
                                    <h5 class="mb-0 fw-semibold">Persyaratan Magang</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Minimum IPK -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="minimum_ipk">
                                                <i class="fas fa-graduation-cap me-1 text-success"></i>Minimum IPK
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="minimum_ipk" id="minimum_ipk"
                                                    class="form-control form-input-enhanced" step="0.01" min="0" max="4"
                                                    placeholder="Contoh: 3.00" required>
                                            </div>
                                            <small class="text-muted">Rentang: 0.00 - 4.00</small>
                                        </div>
                                    </div>

                                    <!-- Pengalaman -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <i class="fas fa-briefcase me-1 text-info"></i>
                                            <label class="form-label fw-bold mb-3">Pengalaman Kerja</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="pengalaman" id="pengalaman"
                                                    class="form-check-input" value="1">
                                            </div>
                                            <small class="text-muted">Aktifkan jika posisi ini memerlukan pengalaman kerja
                                                sebelumnya</small>
                                        </div>
                                    </div>

                                    <!-- Dokumen Persyaratan -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="dokumen_persyaratan">
                                                <i class="fas fa-file-alt me-1 text-primary"></i>Dokumen Persyaratan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="dokumen_persyaratan" id="dokumen_persyaratan"
                                                class="form-control form-textarea-enhanced" rows="3" required
                                                placeholder="Contoh: CV; Surat Pengantar; Transkrip Nilai;"></textarea>
                                            <small class="text-muted">Masukkan dokumen yang diperlukan, pisahkan dengan ';' di akhir setiap dokumen tanpa spasi diawal. Contoh: CV;Surat Pengantar;Transkrip Nilai;</small>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Persyaratan -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold" for="deskripsi_persyaratan">
                                                <i class="fas fa-align-left me-1 text-secondary"></i>Deskripsi Persyaratan
                                                Tambahan
                                            </label>
                                            <textarea name="deskripsi_persyaratan" id="deskripsi_persyaratan"
                                                class="form-control form-textarea-enhanced" rows="4"
                                                placeholder="Jelaskan persyaratan tambahan seperti sertifikasi, portfolio, atau kualifikasi khusus lainnya..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Keahlian -->
                    <div class="col-xl-7 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tools me-2 text-success"></i>
                                        <h5 class="mb-0 fw-semibold">Keahlian yang Dibutuhkan</h5>
                                    </div>
                                    <div class="d-flex align-items-center text-body-secondary">
                                        <i class="fas fa-asterisk me-1" style="font-size: 8px;"></i>
                                        <small>Minimal 1 keahlian harus dipilih</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="keahlianContainer">
                                    <!-- Template keahlian pertama -->
                                    <div class="keahlian-item mb-3 p-3 border rounded-3 bg-light-subtle">
                                        <div class="row g-3">
                                            <div class="col-md-7">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-cog me-1 text-primary"></i>Keahlian
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="keahlian[0][id]"
                                                    class="form-control form-select-enhanced keahlian-select" required>
                                                    <option value="">-- Pilih Keahlian --</option>
                                                    @foreach ($keahlianList as $keahlian)
                                                        <option value="{{ $keahlian->keahlian_id }}">
                                                            {{ $keahlian->nama_keahlian }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-chart-line me-1 text-info"></i>Tingkat
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="keahlian[0][tingkat]"
                                                    class="form-control form-select-enhanced" required>
                                                    <option value="">-- Pilih Tingkat --</option>
                                                    <option value="pemula">🌱 Pemula</option>
                                                    <option value="menengah">📈 Menengah</option>
                                                    <option value="mahir">🎯 Mahir</option>
                                                    <option value="ahli">🏆 Ahli</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-keahlian d-none"
                                                    title="Hapus Keahlian">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Tambah Keahlian -->
                                <div class="d-flex justify-content-start">
                                    <button type="button" id="addKeahlian"
                                        class="btn btn-outline-success d-flex align-items-center">
                                        <i class="fas fa-plus me-2"></i>
                                        <span>Tambah Keahlian</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sticky Footer Navigation -->
        <div class="footer-lanjutan-lowongan">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <!-- Left Side - Back Button -->
                    <div class="footer-nav-left">
                        <button type="button" class="btn btn-footer btn-secondary d-flex align-items-center" id="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>
                            <span class="d-none d-sm-inline">Kembali ke Daftar Lowongan</span>
                        </button>
                    </div>

                    <!-- Center - Progress & Title -->
                    <div class="footer-nav-center d-flex align-items-center gap-3">
                        <div class="footer-title d-none d-lg-block">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-cogs me-2 text-success"></i>
                                <span class="fw-bold text-body">{{ $page->title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Save -->
                    <div class="footer-nav-right">
                        <button type="button" class="btn btn-footer btn-success d-flex align-items-center"
                            id="btn-save-finish">
                            <i class="fas fa-check-circle me-2"></i>
                            <span class="d-none d-sm-inline">Selesai & Simpan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite (['resources/css/lowongan/lanjutan.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formLanjutan');
            const container = document.getElementById('keahlianContainer');
            let index = 1;
            function updateRemoveButtons() {
                const items = container.querySelectorAll('.keahlian-item');
                items.forEach((item, idx) => {
                    const removeBtn = item.querySelector('.remove-keahlian');
                    if (items.length > 1) {
                        removeBtn.classList.remove('d-none');
                    } else {
                        removeBtn.classList.add('d-none');
                    }
                });
            }

            function updateIndices() {
                const items = container.querySelectorAll('.keahlian-item');
                items.forEach((item, idx) => {
                    const selects = item.querySelectorAll('select');
                    selects[0].name = `keahlian[${idx}][id]`;
                    selects[1].name = `keahlian[${idx}][tingkat]`;
                });
                index = items.length;
            }

            function updateKeahlianOptions() {
                const allSelects = container.querySelectorAll('select[name$="[id]"]');
                const selectedValues = Array.from(allSelects).map(s => s.value).filter(v => v);
                allSelects.forEach(select => {
                    const currentValue = select.value;
                    Array.from(select.options).forEach(option => {
                        if (!option.value) return;
                        if (option.value === currentValue) {
                            option.hidden = false;
                        } else if (selectedValues.includes(option.value)) {
                            option.hidden = true;
                        } else {
                            option.hidden = false;
                        }
                    });
                });
            }

            // menambah keahlian
            document.getElementById('addKeahlian').addEventListener('click', function () {
                const template = container.querySelector('.keahlian-item');
                const newItem = template.cloneNode(true);

                const selects = newItem.querySelectorAll('select');
                selects.forEach(select => {
                    select.value = '';
                    select.classList.remove('is-invalid', 'is-valid');
                });

                selects[0].name = `keahlian[${index}][id]`;
                selects[1].name = `keahlian[${index}][tingkat]`;

                const removeBtn = newItem.querySelector('.remove-keahlian');
                removeBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Hapus Keahlian?',
                        text: 'Keahlian ini akan dihapus dari daftar',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            newItem.style.animation = 'fadeOut 0.3s ease forwards';
                            setTimeout(() => {
                                newItem.remove();
                                updateIndices();
                                updateRemoveButtons();
                            }, 300);
                        }
                    });
                });

                newItem.style.animation = 'fadeInUp 0.5s ease forwards';
                container.appendChild(newItem);

                index++;
                updateRemoveButtons();
                updateKeahlianOptions();

                newItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });

            // menghapus keahlian
            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-keahlian')) {
                    const item = e.target.closest('.keahlian-item');
                    Swal.fire({
                        title: 'Hapus Keahlian?',
                        text: 'Keahlian ini akan dihapus dari daftar',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            item.style.animation = 'fadeOut 0.3s ease forwards';
                            setTimeout(() => {
                                item.remove();
                                updateIndices();
                                updateRemoveButtons();
                                updateKeahlianOptions();
                            }, 300);
                        }
                    });
                }
            });
            updateRemoveButtons();
            updateKeahlianOptions();

            // Reset form
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

                        // Reset keahlian to only one item
                        const items = container.querySelectorAll('.keahlian-item');
                        for (let i = items.length - 1; i > 0; i--) {
                            items[i].remove();
                        }

                        // Reset first item
                        const firstItem = container.querySelector('.keahlian-item');
                        const selects = firstItem.querySelectorAll('select');
                        selects.forEach(select => {
                            select.value = '';
                            select.classList.remove('is-invalid', 'is-valid');
                        });

                        // Remove validation classes from all inputs
                        const formInputs = form.querySelectorAll('input, select, textarea');
                        formInputs.forEach(input => {
                            input.classList.remove('is-invalid', 'is-valid');
                        });

                        updateIndices();
                        updateRemoveButtons();
                        updateKeahlianOptions();

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

            // kembali ke form utama
            document.getElementById('btn-back').addEventListener('click', function () {
                Swal.fire({
                    title: 'Kembali ke Daftar Lowongan?',
                    text: 'Data yang belum disimpan akan hilang!    ',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, kembali!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `{{ route('admin.magang.lowongan.index') }}`;
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

                // Validasi at least one keahlian
                const keahlianSelects = form.querySelectorAll('.keahlian-select');
                let hasKeahlian = false;
                keahlianSelects.forEach(select => {
                    if (select.value.trim()) {
                        hasKeahlian = true;
                    }
                });

                if (!hasKeahlian) {
                    keahlianSelects[0].classList.add('is-invalid');
                    isValid = false;

                    Swal.fire({
                        icon: 'warning',
                        title: 'Keahlian Diperlukan',
                        text: 'Minimal 1 keahlian harus dipilih untuk posisi ini'
                    });
                }

                return isValid;
            }

            // Enhanced form submission handler
            function submitForm(clickedButton = null) {
                if (!validateForm()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Form Tidak Valid',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                    });
                    return;
                }

                // Collect all skills data
                const keahlianData = [];
                const keahlianItems = container.querySelectorAll('.keahlian-item');

                keahlianItems.forEach((item, index) => {
                    const idSelect = item.querySelector('select[name^="keahlian["][name$="[id]"]');
                    const tingkatSelect = item.querySelector(
                        'select[name^="keahlian["][name$="[tingkat]"]');

                    if (idSelect.value && tingkatSelect.value) {
                        keahlianData.push({
                            id: idSelect.value,
                            tingkat: tingkatSelect.value
                        });
                    }
                });

                // Prepare form data as FormData for file upload
                const formData = new FormData(form);
                formData.set('minimum_ipk', document.getElementById('minimum_ipk').value);
                formData.set('deskripsi_persyaratan', document.getElementById('deskripsi_persyaratan').value);
                formData.set('pengalaman', document.getElementById('pengalaman').checked ? 1 : 0);
                formData.delete('keahlian[0][id]'); // Remove default if exists
                formData.delete('keahlian[0][tingkat]');
                formData.delete('keahlian');
                keahlianData.forEach((k, i) => {
                    formData.append(`keahlian[${i}][id]`, k.id);
                    formData.append(`keahlian[${i}][tingkat]`, k.tingkat);
                });

                const submitBtn = clickedButton || document.getElementById('btn-save-finish');
                const originalHtml = submitBtn.innerHTML;

                // Show loading state
                const loadingHtml = '<i class="fas fa-spinner fa-spin me-2"></i><span>Menyimpan...</span>';
                submitBtn.innerHTML = loadingHtml;
                submitBtn.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                    .then(async response => {
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            throw new Error(text || 'Server returned non-JSON response');
                        }

                        const data = await response.json();

                        if (!response.ok) {
                            let errorMessages = '';
                            if (data.errors) {
                                for (const key in data.errors) {
                                    errorMessages += `${data.errors[key].join(', ')}\n`;

                                    const fieldElement = form.querySelector(`[name="${key}"]`) ||
                                        form.querySelector(`[name="${key}[]"]`);
                                    if (fieldElement) {
                                        fieldElement.classList.add('is-invalid');
                                    }
                                }
                            } else if (data.message) {
                                errorMessages = data.message;
                            } else {
                                errorMessages = 'Terjadi kesalahan saat menyimpan data';
                            }

                            throw new Error(errorMessages);
                        }

                        return data;
                    })
                    .then(data => {
                        console.log('Response data:', data);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Data persyaratan dan keahlian berhasil disimpan',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = '{{ route('admin.magang.lowongan.index') }}';
                        });
                    })
                    .catch(error => {
                        console.error('Submit error:', error);
                        if (error.message.includes('<!DOCTYPE html>') || error.message.includes('<html')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                html: 'Terjadi kesalahan server. Silakan coba lagi atau hubungi administrator.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: error.message,
                            });
                        }
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalHtml;
                        submitBtn.disabled = false;
                    });
            }

            document.getElementById('btn-save-finish').addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Save & Finish button clicked');
                submitForm(this);
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

            // Special validation for keahlian selects
            container.addEventListener('change', function (e) {
                if (e.target.classList.contains('keahlian-select')) {
                    const keahlianSelects = form.querySelectorAll('.keahlian-select');
                    let hasKeahlian = false;

                    keahlianSelects.forEach(select => {
                        if (select.value.trim()) {
                            hasKeahlian = true;
                            select.classList.remove('is-invalid');
                            select.classList.add('is-valid');
                        }
                    });

                    if (!hasKeahlian) {
                        e.target.classList.add('is-invalid');
                    }
                }
            });

            // IPK validation
            const ipkInput = document.getElementById('minimum_ipk');
            if (ipkInput) {
                ipkInput.addEventListener('input', function () {
                    const value = parseFloat(this.value);
                    if (value < 0 || value > 4) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            }
        });
    </script>
@endpush