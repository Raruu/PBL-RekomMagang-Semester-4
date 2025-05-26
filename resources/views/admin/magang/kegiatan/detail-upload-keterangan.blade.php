<div class="d-flex flex-column text-start align-items-start p-3 w-100 display-detail" style="opacity: 0">
    <div class="d-flex flex-column w-100" style="height: 70vh">
        <form action="{{ route('admin.magang.kegiatan.upload.keterangan') }}" id="form-dokumen" method="POST"
            class="card mb-4 w-100" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="pengajuan_id" value="{{ $pengajuanMagang->pengajuan_id }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>Upload</strong> &#8226; <span class="small">Keterangan Magang</span></span>
                <div class="d-flex flex-row gap-2">
                    <button type="button" class="btn btn-outline-danger {{ $pengajuanMagang->file_sertifikat ? '' : 'd-none' }}" onclick="" id="btn-hapus-keterangan">
                        <i class="fa-solid fa-trash-can fa-fw"></i>
                        Hapus
                    </button>

                    <x-btn-submit-spinner disabled id="upload-button" size="22">
                        <i class="fa-solid fa-cloud-arrow-up fa-fw"></i>
                        Upload
                    </x-btn-submit-spinner>
                    <a href="{{ asset($pengajuanMagang->file_sertifikat) }}"
                        class="btn btn-outline-primary {{ $pengajuanMagang->file_sertifikat == null ? 'disabled' : '' }}"
                        download>
                        <i class="fa-solid fa-cloud-arrow-down fa-fw"></i>
                        Download
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input class="form-control" id="keterangan_magang" name="keterangan_magang" type="file"
                        accept=".pdf">
                </div>
            </div>
        </form>
        <div class="w-100 h-100 d-flex flex-column align-items-center">
            <iframe id="iframe-dokumen-keterangan"
                class="w-100 h-100 {{ $pengajuanMagang->file_sertifikat ? '' : 'd-none' }}"
                src="{{ $pengajuanMagang->file_sertifikat ? asset($pengajuanMagang->file_sertifikat) : '' }}"
                allowfullscreen>
            </iframe>

            <h1 class="{{ $pengajuanMagang->file_sertifikat ? 'd-none' : '' }}">Belum ada dokumen</h1>
        </div>
    </div>
</div>
<x-modal-yes-no id="modal-hapus" dismiss="false" static="true" btnTrue="Ya">
    <x-slot name="btnTrue">
        <x-btn-submit-spinner size="22" wrapWithButton="false">
            Hapus
        </x-btn-submit-spinner>
    </x-slot>
    Hapus surat keterangan?
</x-modal-yes-no>
