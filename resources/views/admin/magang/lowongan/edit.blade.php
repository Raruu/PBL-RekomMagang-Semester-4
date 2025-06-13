<div class="modal-content">
    <form id="editLowonganForm" action="{{ route('admin.magang.lowongan.update', ['id' => $lowongan->lowongan_id]) }}"
        method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
            <div class="row g-4">
                <!-- Informasi Dasar -->
                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-primary text-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-address-card me-2"></i>Informasi Dasar
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="edit_perusahaan_id" class="form-label fw-semibold">
                                    Perusahaan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_perusahaan_id" name="perusahaan_id" required>
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach ($perusahaanList as $perusahaan)
                                    <option value="{{ $perusahaan->perusahaan_id }}"
                                        {{ $perusahaan->perusahaan_id == $lowongan->perusahaan_id ? 'selected' : '' }}>
                                        {{ $perusahaan->nama_perusahaan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <input type="hidden" id="edit_lokasi_id" name="lokasi_id" value="{{ $lowongan->lokasi_id }}" readonly>
                                <label for="edit_lokasi_alamat" class="form-label fw-semibold">
                                    Lokasi <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-dot"></i>
                                    </span>
                                    <input type="text" class="form-control" id="edit_lokasi_alamat" name="lokasi_alamat"
                                        value="{{ $lowongan->lokasi->alamat }}" required readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_judul_lowongan" class="form-label fw-semibold">
                                    Judul Lowongan <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_judul_lowongan" name="judul_lowongan"
                                    value="{{ $lowongan->judul_lowongan }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_judul_posisi" class="form-label fw-semibold">
                                    Judul Posisi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_judul_posisi" name="judul_posisi"
                                    value="{{ $lowongan->judul_posisi }}" required>
                            </div>

                            <div class="mb-0">
                                <label for="edit_deskripsi" class="form-label fw-semibold">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4"
                                    placeholder="Masukkan deskripsi lowongan..." required>{{ $lowongan->deskripsi }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Lowongan -->
                <div class="col-lg-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-header bg-success text-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Detail Lowongan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="edit_gaji" class="form-label fw-semibold">Gaji</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit_gaji" name="gaji" min="0"
                                        value="{{ $lowongan->gaji }}" placeholder="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_kuota" class="form-label fw-semibold">
                                    Kuota <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="number" class="form-control" id="edit_kuota" name="kuota" min="1"
                                        value="{{ $lowongan->kuota }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_tipe_kerja_lowongan" class="form-label fw-semibold">
                                    Tipe Kerja <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_tipe_kerja_lowongan" name="tipe_kerja_lowongan" required>
                                    @foreach ($tipeKerja as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $lowongan->tipe_kerja_lowongan == $key ? 'selected' : '' }}>{{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_batas_pendaftaran" class="form-label fw-semibold">
                                    Batas Pendaftaran <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input type="date" class="form-control" id="edit_batas_pendaftaran" name="batas_pendaftaran"
                                        value="{{ $lowongan->batas_pendaftaran }}" required>
                                </div>
                            </div>

                            <div class="mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1"
                                        {{ $lowongan->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="edit_is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Persyaratan -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-1 shadow-sm">
                        <div class="card-header bg-warning text-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-tasks me-2"></i>Persyaratan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_minimum_ipk" class="form-label fw-semibold">Minimum IPK</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                Score
                                            </span>
                                            <input type="number" class="form-control" id="edit_minimum_ipk" name="minimum_ipk"
                                                value="{{ $lowongan->persyaratanMagang->minimum_ipk }}" step="0.01" min="0" max="4"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="edit_pengalaman" name="pengalaman"
                                                value="1" {{ $lowongan->persyaratanMagang->pengalaman ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="edit_pengalaman">
                                                Memerlukan Pengalaman
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-0">
                                        <label for="edit_deskripsi_persyaratan" class="form-label fw-semibold">
                                            Deskripsi Persyaratan <br/><span class="text-muted" style="font-size: 12px;">Pisahkan dengan tanda titik koma (;)</span>
                                        </label>
                                        <textarea class="form-control" id="edit_deskripsi_persyaratan" name="deskripsi_persyaratan"
                                            rows="3" placeholder="Contoh: Mahasiswa aktif; Bersedia belajar teknologi baru;">{{ $lowongan->persyaratanMagang->deskripsi_persyaratan }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Preview Dokumen Persyaratan -->
                            @if (!empty($lowongan->persyaratanMagang->dokumen_persyaratan))
                                <div class="alert alert-light mt-5" id="dokumen-persyaratan-preview">
                                    <strong>Dokumen Persyaratan Sebelumnya:</strong>
                                    <ul class="mb-0">
                                        @foreach (explode(';', $lowongan->persyaratanMagang->dokumen_persyaratan) as $dokumen)
                                            @if (trim($dokumen) !== '')
                                                <li><i class="fas fa-file-alt me-2 text-success"></i>{{ trim($dokumen) }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="edit_dokumen_persyaratan" class="form-label fw-semibold">Dokumen Persyaratan <span
                                        class="text-danger">*</span> <br/><span class="text-muted" style="font-size: 12px;">Pisahkan dengan tanda titik koma (;)</span></label>
                                <textarea class="form-control" id="edit_dokumen_persyaratan" name="dokumen_persyaratan" rows="3"
                                    placeholder="Contoh: CV; Transkrip Nilai; Surat Rekomendasi">{{ $lowongan->persyaratanMagang->dokumen_persyaratan }}</textarea>
                                <small class="form-text text-muted">Pisahkan dokumen dengan tanda titik koma (;)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keahlian -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-1 shadow-sm">
                        <div class="card-header bg-secondary text-white border-0">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-tools me-2"></i>Keahlian yang Dibutuhkan
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                            <div class="mb-3">
                                <label for="keahlian-{{ $keytingkatKemampuan }}" class="form-label fw-bold text-primary">
                                    {{ $tingkatKemampuan }}
                                </label>
                                <input type="text" class="form-control" name="keahlian-{{ $keytingkatKemampuan }}"
                                    id="keahlian-{{ $keytingkatKemampuan }}"
                                    value="{{ implode(', ', $keahlianList->where('kemampuan_minimum', $keytingkatKemampuan)->pluck('keahlian.nama_keahlian')->toArray()) }}"
                                    placeholder="pilih keahlian">
                                <div id="error-keahlian-{{ $keytingkatKemampuan }}" class="invalid-feedback"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card-header {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}

.form-switch .form-check-input {
    width: 2em;
    height: 1em;
}

.invalid-feedback {
    display: block;
}

body.dark-mode .modal-content .card-header.bg-primary,
[data-coreui-theme="dark"] .modal-content .card-header.bg-primary {
    background-color: #0d6efd !important;
    color: #fff !important;
}
body.dark-mode .modal-content .card-header.bg-success,
[data-coreui-theme="dark"] .modal-content .card-header.bg-success {
    background-color: #198754 !important;
    color: #fff !important;
}
body.dark-mode .modal-content .card-header.bg-warning,
[data-coreui-theme="dark"] .modal-content .card-header.bg-warning {
    background-color: #ffc107 !important;
    color: #fff !important;
}
body.dark-mode .modal-content .card-header.bg-secondary,
[data-coreui-theme="dark"] .modal-content .card-header.bg-secondary {
    background-color:rgb(88, 88, 88) !important;
    color: #fff !important;
}
</style>