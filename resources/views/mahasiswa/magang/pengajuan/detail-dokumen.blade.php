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
        <div class="card background-hoverable"
            onclick="lowonganOpenModalPreviewPdf('{{ asset($dokumen->path_file) }}', '{{ $dokumen->jenis_dokumen }}')">
            <div class="card-body d-flex flex-row justify-content-between">
                <h5 class="card-title my-auto">{{ $dokumen->jenis_dokumen }}</h5>
                <div class="d-flex flex-row gap-2 align-items-center">
                    {{-- <button class="btn btn-outline-info"
                        onclick="event.stopPropagation(); lowonganOpenModalPreviewPdf('{{ asset($dokumen->path_file) }}', '{{ $dokumen->jenis_dokumen }}')">
                        <i class="fas fa-eye"></i>
                    </button> --}}
                    <a href="{{ asset($dokumen->path_file) }}" class="btn btn-outline-primary"
                        onclick="event.stopPropagation();" download>
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

</div>


<x-page-modal id="modal-pdf-preview" title="Preview Dokumen" class="modal-xl">
    <iframe class="pdf_preview" src="" width="100%" style="height: 70vh"></iframe>
</x-page-modal>
