<div class="card-body d-flex flex-column gap-2 flex-fill display-detail justify-content-center" style="opacity: 0; height: 70vh">
    @if (!$pengajuanMagang->file_sertifikat)
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('imgs/sanhua-froze.webp') }}" alt="ice" style="width: 16rem">
            <h1 class="text-center">Belum Ada dokumen</h1>
        </div>
    @else
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Surat Keterangan Magang</h4>
            <a class="btn btn-primary d-flex gap-2 justify-content-center align-items-center" target="_blank"
                href="{{ asset($pengajuanMagang->file_sertifikat) }}">
                <i class="fas fa-external-link-alt"></i>
                <span>Buka di tab baru</span>
            </a>
        </div>
        <div class="w-100 h-100 d-flex flex-column align-items-center">
            <iframe id="iframe-dokumen-cv" class="w-100 h-100 {{ $pengajuanMagang->file_sertifikat ? '' : 'd-none' }}"
                src="{{ $pengajuanMagang->file_sertifikat ? asset($pengajuanMagang->file_sertifikat) : '' }}"
                allowfullscreen>
            </iframe>

            <h1 class="{{ $pengajuanMagang->file_sertifikat ? 'd-none' : '' }}">Link-nya ada, file engga tau</h1>
        </div>
    @endif
</div>
