<div class="d-flex flex-column">
    <h5 class="text-muted">Bidang: <span class="fw-bold">{{ $bidangIndustri->nama }}</span></h5>
    <h6>Perusahaan dengan bidang ini: </h6>
    <div class="d-flex flex-column gap-2">
        @if ($perusahaan->isNotEmpty())
            @foreach ($perusahaan as $index => $value)
                <div class="d-flex flex-column gap-1">
                    <h6 class="fw-bold mb-0">{{ $index + 1 }}. {{ $value->nama_perusahaan }}</h6>
                    <a href="https://maps.google.com/?q={{ $value->lokasi->latitude }},{{ $value->lokasi->longitude }}"
                        target="_blank">
                        {{ $value->lokasi->alamat }}
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-muted">Tidak ada perusahaan</p>
        @endif
    </div>
</div>
