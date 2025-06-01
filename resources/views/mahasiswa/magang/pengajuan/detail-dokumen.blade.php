<div class="card-body d-flex flex-column gap-2 flex-fill display-detail" style="opacity: 0">
    <div class="d-flex flex-column gap-1">
        <label for="catatan_admin" class="form-label fw-bold">Catatan Admin</label>
        <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_admin ?? '-' }}</textarea>
    </div>
    <div class="d-flex flex-column gap-1">
        <label for="catatan_mahasiswa" class="form-label fw-bold">Catatan Mahasiswa</label>
        <textarea class="form-control" id="catatan_mahasiswa" name="catatan_mahasiswa" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_mahasiswa ?? '-' }}</textarea>
    </div>
    @if (!$pengajuanMagang->dokumenPengajuan->isEmpty())
        <p class="fw-bold mb-1">Dokumen Pendukung</p>
    @endif
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
