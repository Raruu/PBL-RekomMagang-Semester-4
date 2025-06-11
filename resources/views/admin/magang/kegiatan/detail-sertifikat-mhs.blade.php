<div class="card-body d-flex flex-column gap-2 flex-fill display-detail justify-content-center" style="opacity: 0;">
    <div id="form-dokumen" class="card mb-4 w-100" enctype="multipart/form-data">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Surat Keterangan Magang</strong></span>
            <div class="d-flex flex-row gap-2">
                <a href="{{ asset($pengajuanMagang->file_sertifikat) }}" class="btn btn-outline-primary"
                    {{ $pengajuanMagang->file_sertifikat == null ? 'disabled' : '' }}" download>
                    <svg class="icon">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-download') }}"></use>
                    </svg>
                    Download
                </a>
            </div>
        </div>
    </div>
    <iframe class="iframe_file_sertifikat w-100 {{ $pengajuanMagang->file_sertifikat == null ? 'd-none' : '' }}"
        style="height: 70vh" src="{{ asset($pengajuanMagang->file_sertifikat) }}">
        <h4>Dokument tidak tampil?</h4>
    </iframe>
</div>
