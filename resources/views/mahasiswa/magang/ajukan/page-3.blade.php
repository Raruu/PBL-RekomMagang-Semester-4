<div class="container-lg flex-fill d-flex flex-column h-100">
    <h4 class="fw-bold mb-3">Konfirmasi Lowongan Berikut</h4>
    <div class="d-flex flex-row gap-2 card p-3 mb-4 w-100">
        @include('mahasiswa.magang.detail-lowongan')
        <div class="d-flex flex-column gap-1 text-start card-body">
            <h3 class="fw-bold mb-0">Informasi Perusahaan</h3>
            <p class="mb-0 ">
                {{ $lowongan->perusahaan->nama_perusahaan }}
            </p>
            <p class="mb-0 "><span class="text-muted">Bidang Industri:</span>
                {{ $lowongan->perusahaan->bidang_industri }}
            </p>

            <a class="mb-0 " target="_blank" href="{{ $lowongan->perusahaan->website }}">
                {{ $lowongan->perusahaan->website }}
            </a>
            <a class="mb-0 " href="mailto:{{ $lowongan->perusahaan->kontak_email }}">
                {{ $lowongan->perusahaan->kontak_email }}
            </a>
            <p class="mb-0 "><span class="text-muted">Telepon:</span>
                {{ $lowongan->perusahaan->kontak_telepon }}
            </p>
        </div>
    </div>
</div>
