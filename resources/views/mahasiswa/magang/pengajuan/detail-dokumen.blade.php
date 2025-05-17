<div class="card-body d-flex flex-column gap-2 flex-fill">
    <div class="d-flex flex-column gap-1">
        <label for="catatan_dosen" class="form-label fw-bold">Catatan Dosen</label>
        <textarea class="form-control" id="catatan_dosen" name="catatan_dosen" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_dosen ?? '-' }}</textarea>
    </div>
    <div class="d-flex flex-column gap-1">
        <label for="catatan_mahasiswa" class="form-label fw-bold">Catatan Mahasiswa</label>
        <textarea class="form-control" id="catatan_mahasiswa" name="catatan_mahasiswa" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_mahasiswa ?? '-' }}</textarea>
    </div>
    <p class="fw-bold mb-1">Dokumen Pendukung</p>
    @foreach ($pengajuanMagang->dokumenPengajuan as $dokumen)
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $dokumen->jenis_dokumen }}" readonly disabled>
            <button class="btn btn-outline-primary" type="button"
                onclick="window.open('{{ asset($dokumen->path_file) }}')">
                <svg class="icon">
                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-external-link') }}">
                    </use>
                </svg>
            </button>
        </div>
    @endforeach

</div>
