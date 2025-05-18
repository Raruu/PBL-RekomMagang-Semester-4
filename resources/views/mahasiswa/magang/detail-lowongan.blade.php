<div class="card-body d-flex flex-column gap-2 flex-fill">
    <div class="d-flex flex-row gap-2 align-items-end justify-content-between">
        <div class="d-flex flex-row gap-2 align-items-end">
            <h3 class="fw-bold mb-0">{{ $lowongan->judul_lowongan }} </h3>
        </div>
    </div>
    <div class="d-flex flex-row gap-2">
        <span class="badge my-auto bg-{{ $lowongan->is_active ? 'success' : 'danger' }}">
            {{ $lowongan->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </span>
        <p class="mb-0 text-muted">
            Batas Pendaftaran: {{ $lowongan->batas_pendaftaran }} atau          
            <strong>{{ $days }}</strong>
            hari lagi
        </p>
    </div>
    <div class="d-flex flex-column gap-2 mt-1">
        <h5 class="fw-bold mb-0"><span class="text-muted">Posisi:</span> {{ $lowongan->judul_posisi }} </h5>
        <p>
            {{ $lowongan->deskripsi }}
        </p>
    </div>
    <div>
        <h5 class="fw-bold mb-0">Persyaratan Magang</h5>
        <ul class="list-unstyled">
            @foreach (explode("\n", $lowongan->persyaratanMagang->deskripsi_persyaratan) as $deskripsiPersyaratan)
                <li>&#8226; {{ $deskripsiPersyaratan }}</li>
            @endforeach
        </ul>
    </div>
    <div>
        <h5 class="fw-bold mb-0">Skill Minimum</h5>
        <div class="d-flex flex-column gap-2">
            @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                @php
                    $keahlianLowongan = $lowongan->keahlianLowongan->where('kemampuan_minimum', $keytingkatKemampuan);
                @endphp
                @if (!$keahlianLowongan->isEmpty())
                    <div class="d-flex flex-column">
                        <p class="fw-bold mb-0"> &#8226; <span>{{ $tingkatKemampuan }}</span> </p>
                        <div class="d-flex flex-row gap-1 flex-wrap ps-2 _keahlian">
                            @foreach ($keahlianLowongan as $keahlianMahasiswa)
                                <span
                                    class="badge badge-sm 
                                            @if ($keytingkatKemampuan == 'ahli') bg-danger 
                                            @elseif ($keytingkatKemampuan == 'mahir') bg-warning 
                                            @elseif ($keytingkatKemampuan == 'menengah') bg-primary 
                                            @else bg-info @endif">{{ $keahlianMahasiswa->keahlian->nama_keahlian }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="d-flex flex-column gap-1 mt-1">
        <h5 class="fw-bold mb-0">Tentang Lowongan</h5>
        <div class="d-flex flex-row gap-3">
            <div class="d-flex flex-column gap-0 align-items-center">
                <div class="d-flex flex-row gap-1 align-content-center justify-content-center">
                    <svg class="icon my-auto ">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}"></use>
                    </svg>
                    <p class="mb-0"> Tipe Kerja </p>
                </div>
                <div>
                    <span class="badge bg-primary my-auto">
                        {{ ucfirst($lowongan->tipe_kerja_lowongan) }}
                    </span>
                </div>
            </div>
            @if (isset($score))
                <div class="d-flex flex-column gap-0 align-items-center">
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-center">
                        <p class="mb-0">%</p>
                        <p class="mb-0">Skor Kecocokan</p>
                    </div>
                    <div>
                        <span
                            class="badge fw-bold my-auto bg-{{ $score > 0.7 ? 'success' : ($score > 0.5 ? 'warning' : 'danger') }}">
                            {{ $score }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex flex-column gap-1 mt-1">
        <h5 class="fw-bold mb-0">Tanggal</h5>
        <div class="d-flex flex-row gap-1 align-content-center justify-content-start">
            <svg class="icon my-auto ">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-clock') }}"></use>
            </svg>
            <p class="mb-0 text-muted"> Mulai: </p>
            <div>
                <p class="mb-0">
                    {{ $lowongan->tanggal_mulai }}
                </p>
            </div>
        </div>
        <div class="d-flex flex-row gap-1 align-content-center justify-content-start">
            <svg class="icon my-auto ">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-flag-alt') }}"></use>
            </svg>
            <p class="mb-0 text-muted"> Selesai: </p>
            <div>
                <p class="mb-0">
                    {{ $lowongan->tanggal_selesai }}
                </p>
            </div>
        </div>
    </div>
</div>
