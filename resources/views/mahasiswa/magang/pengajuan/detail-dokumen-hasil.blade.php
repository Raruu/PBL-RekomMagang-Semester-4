<div class="card-body d-flex flex-column gap-2 flex-fill display-detail justify-content-center" style="opacity: 0;">
    <form action="{{ route('mahasiswa.magang.pengajuan.uploadHasil', $pengajuanMagang->pengajuan_id) }}" id="form-dokumen"
        method="POST" class="card mb-4 w-100" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Upload Surat Keterangan Magang</strong> &#8226; <span class="small">MAX 2MB</span></span>
            <div class="d-flex flex-row gap-2">
                <x-btn-submit-spinner disabled id="upload-button" size="22">
                    <svg class="icon">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-upload') }}"></use>
                    </svg>
                    Upload
                </x-btn-submit-spinner>
                <a href="{{ asset($pengajuanMagang->file_sertifikat) }}"
                    class="btn btn-outline-primary {{ $pengajuanMagang->file_sertifikat == null ? 'disabled' : '' }}"
                    download>
                    <svg class="icon">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-download') }}"></use>
                    </svg>
                    Download
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input class="form-control" id="file_sertifikat" name="file_sertifikat" type="file" accept=".pdf">
            </div>
        </div>
    </form>
    <iframe class="iframe_file_sertifikat w-100 {{ $pengajuanMagang->file_sertifikat == null ? 'd-none' : '' }}"
        style="height: 70vh" src="{{ asset($pengajuanMagang->file_sertifikat) }}">
        <h4>Dokument tidak tampil?</h4>
    </iframe>
</div>
