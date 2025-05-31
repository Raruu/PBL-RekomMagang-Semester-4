<div class="d-flex flex-column gap-2 pb-4">
        <div class="d-flex flex-row w-100">
            @include('mahasiswa.magang.detail-lowongan')

            <div class="card m-4" style="height: fit-content; max-width: 250px;">
                <div class="card-body d-flex flex-column flex-fill text-center">                   
                    <h4 class="mb-0">
                        <span class="badge bg-info mb-0  {{ $lowongan->gaji > 0 ? 'bg-info' : 'bg-danger' }}">
                            {{ $lowongan->gaji > 0 ? 'Rp. ' . $lowongan->gaji : 'Tidak ada gaji' }}
                        </span>
                    </h4>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1 text-start">
                        <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                        <p class="mb-0 small">
                            {{ $lowongan->perusahaanMitra->nama_perusahaan }}
                        </p>
                        <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                            {{ $lowongan->perusahaanMitra->bidang_industri }}
                        </p>

                        <a class="mb-0 small" target="_blank" href="{{ $lowongan->perusahaanMitra->website }}">
                            {{ $lowongan->perusahaanMitra->website }}
                        </a>
                        <a class="mb-0 small" href="mailto:{{ $lowongan->perusahaanMitra->kontak_email }}">
                            {{ $lowongan->perusahaanMitra->kontak_email }}
                        </a>
                        <p class="mb-0 small"><span class="text-muted">Telepon:</span>
                            {{ $lowongan->perusahaanMitra->kontak_telepon }}
                        </p>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1 text-start">
                        <h6 class="fw-bold mb-0">Lokasi</h6>
                        <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                            target="_blank">
                            {{ $lokasi->alamat }}
                        </a>
                        <p class="mb-0 small"><span class="text-muted">Jarak dengan preferensi:<br /></span>
                            {{ number_format($jarak, 2) }} <span class="text-muted fw-bold">KM</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>        
    </div>