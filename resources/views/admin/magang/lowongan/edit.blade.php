<form id="editLowonganForm" action="{{ route('admin.magang.lowongan.update', ['id' => $lowongan->lowongan_id]) }}"
    method="POST">
    @csrf
    @method('PUT')

    <div class="d-flex flex-row gap-4">
        <!-- Informasi Dasar -->
        <div class="d-flex flex-column gap-3 flex-fill">
            <h6 class="text-primary mb-2">Informasi Dasar</h6>

            <div>
                <label for="edit_perusahaan_id" class="form-label">Perusahaan <span class="text-danger">*</span></label>
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

            <div>
                <input type="hidden" id="edit_lokasi_id" name="lokasi_id" value="{{ $lowongan->lokasi_id }}" readonly>
                <label for="edit_lokasi_alamat" class="form-label">Lokasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_lokasi_alamat" name="lokasi_alamat"
                    value="{{ $lowongan->lokasi->alamat }}" required readonly>
            </div>

            <div>
                <label for="edit_judul_lowongan" class="form-label">Judul Lowongan <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_judul_lowongan" name="judul_lowongan"
                    value="{{ $lowongan->judul_lowongan }}" required>
            </div>

            <div>
                <label for="edit_judul_posisi" class="form-label">Judul Posisi <span
                        class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_judul_posisi" name="judul_posisi"
                    value="{{ $lowongan->judul_posisi }}" required>
            </div>

            <div>
                <label for="edit_deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4" required>{{ $lowongan->deskripsi }}</textarea>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 flex-fill">
            <h6 class="text-primary mb-2">Detail Lowongan</h6>

            <div>
                <label for="edit_gaji" class="form-label">Gaji</label>
                <input type="number" class="form-control" id="edit_gaji" name="gaji" min="0"
                    value="{{ $lowongan->gaji }}">
            </div>

            <div>
                <label for="edit_kuota" class="form-label">Kuota <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="edit_kuota" name="kuota" min="1"
                    value="{{ $lowongan->kuota }}" required>
            </div>

            <div>
                <label for="edit_tipe_kerja_lowongan" class="form-label">Tipe Kerja <span
                        class="text-danger">*</span></label>
                <select class="form-select" id="edit_tipe_kerja_lowongan" name="tipe_kerja_lowongan" required>
                    @foreach ($tipeKerja as $key => $value)
                        <option value="{{ $key }}"
                            {{ $lowongan->tipe_kerja_lowongan == $key ? 'selected' : '' }}>{{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_batas_pendaftaran" class="form-label">Batas Pendaftaran <span
                        class="text-danger">*</span></label>
                <input type="date" class="form-control" id="edit_batas_pendaftaran" name="batas_pendaftaran"
                    value="{{ $lowongan->batas_pendaftaran }}" required>
            </div>

            <div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1"
                        {{ $lowongan->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="edit_is_active">
                        Status Aktif
                    </label>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Persyaratan -->
    <div class="d-flex flex-column gap-3">
        <h6 class="text-primary mb-2">Persyaratan</h6>

        <div class="d-flex flex-row gap-3">
            <div class="flex-fill">
                <div class="mb-3">
                    <label for="edit_minimum_ipk" class="form-label">Minimum IPK</label>
                    <input type="number" class="form-control" id="edit_minimum_ipk" name="minimum_ipk"
                        value="{{ $lowongan->persyaratanMagang->minimum_ipk }}" step="0.01" min="0"
                        max="4">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_pengalaman" name="pengalaman" value="1"
                            {{ $lowongan->persyaratanMagang->pengalaman ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_pengalaman">
                            Memerlukan Pengalaman
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex-fill">
                <div class="mb-3">
                    <label for="edit_deskripsi_persyaratan" class="form-label">Deskripsi
                        Persyaratan</label>
                    <textarea class="form-control" id="edit_deskripsi_persyaratan" name="deskripsi_persyaratan" rows="3">{{ $lowongan->persyaratanMagang->deskripsi_persyaratan }}</textarea>
                </div>
            </div>
        </div>

        <hr>

        <!-- Keahlian -->
        <div class="d-flex flex-column gap-3">
            <h6 class="text-primary mb-2">Keahlian yang Dibutuhkan</h6>
            <div class="d-flex flex-column text-start  p-1" style="height: fit-content;">
                @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                    <div class="mb-3 w-100">
                        <p class="mb-0 fw-bold">{{ $tingkatKemampuan }}</p>
                        <input type="text" class="form-control" name="keahlian-{{ $keytingkatKemampuan }}"
                            id="keahlian-{{ $keytingkatKemampuan }}"
                            value="{{ implode(', ', $keahlianList->where('kemampuan_minimum', $keytingkatKemampuan)->pluck('keahlian.nama_keahlian')->toArray()) }}">
                        <div id="error-keahlian-{{ $keytingkatKemampuan }}" class="text-danger"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>
