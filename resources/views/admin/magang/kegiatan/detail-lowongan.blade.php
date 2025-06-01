<div class="card-body display-detail d-flex flex-column gap-2 w-100 flex-fill" style="opacity: 0">
    <div class="w-100 flex-fill d-flex flex-row">
        <div class="d-flex flex-column gap-2 flex-fill">
            <div class="d-flex flex-row gap-2 align-items-end justify-content-between">
                <div class="d-flex flex-row gap-2 align-items-end">
                    <h3 class="fw-bold mb-0">{{ $pengajuanMagang->lowonganMagang->judul_lowongan }} </h3>
                </div>
            </div>
            <div class="d-flex flex-column gap-2 mt-1">
                <h5 class="fw-bold mb-0"><span class="text-muted">Posisi:</span>
                    {{ $pengajuanMagang->lowonganMagang->judul_posisi }} </h5>
                <p>
                    {!! nl2br(e($pengajuanMagang->lowonganMagang->deskripsi)) !!}
                </p>
            </div>
            <div class="d-flex flex-row">
                <div>
                    <h5 class="fw-bold mb-0">Persyaratan Magang</h5>
                    <ul class="list-unstyled">
                        @foreach (explode(';', $pengajuanMagang->lowonganMagang->persyaratanMagang->deskripsi_persyaratan) as $deskripsiPersyaratan)
                            <li>&#8226; {{ $deskripsiPersyaratan }}</li>
                        @endforeach
                    </ul>
                </div>
                @if ($pengajuanMagang->lowonganMagang->persyaratanMagang->dokumen_persyaratan)
                    <div class="mx-auto">
                        <h5 class="fw-bold mb-0">Persyaratan Dokumen</h5>
                        <ul class="list-unstyled">
                            @foreach (explode(';', $pengajuanMagang->lowonganMagang->persyaratanMagang->dokumen_persyaratan) as $dokumenPersyaratan)
                                <li>&#8226; {{ $dokumenPersyaratan }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div>
                <h5 class="fw-bold mb-0">Skill Minimum</h5>
                <div class="d-flex flex-column gap-2">
                    @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                        @php
                            $keahlianLowongan = $pengajuanMagang->lowonganMagang->keahlianLowongan->where(
                                'kemampuan_minimum',
                                $keytingkatKemampuan,
                            );
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
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                                </use>
                            </svg>
                            <p class="mb-0"> Tipe Kerja </p>
                        </div>
                        <div>
                            <span class="badge bg-primary my-auto">
                                {{ ucfirst($pengajuanMagang->lowonganMagang->tipe_kerja_lowongan) }}
                            </span>
                        </div>
                    </div>
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
                            {{ \Carbon\Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_mulai)->format('d/m/Y') }}
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
                            {{ \Carbon\Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_selesai)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card m-3" style="height: fit-content; max-width: 250px;">
            <div class="card-body d-flex flex-column flex-fill text-center">
                <div class="d-flex flex-column gap-1 mt-1">
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-start pt-1 px-1">
                        <svg class="icon my-auto ">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-clock') }}"></use>
                        </svg>
                        <p class="mb-0 text-muted"> Pengajuan: </p>
                        <div>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($pengajuanMagang->tanggal_pengajuan)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <hr class="my-2">
                <h4 class="mb-0">
                    <span
                        class="badge bg-info mb-0  {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'bg-info' : 'bg-danger' }}">
                        {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'Rp. ' . $pengajuanMagang->lowonganMagang->gaji : 'Tidak ada gaji' }}
                    </span>
                </h4>
                <hr class="my-2">
                <div class="d-flex flex-column gap-1 text-start">
                    <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                    <p class="mb-0 small">
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->nama_perusahaan }}
                    </p>
                    <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->bidang_industri }}
                    </p>

                    <a class="mb-0 small" target="_blank"
                        href="{{ $pengajuanMagang->lowonganMagang->perusahaanMitra->website }}">
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->website }}
                    </a>
                    <a class="mb-0 small"
                        href="mailto:{{ $pengajuanMagang->lowonganMagang->perusahaanMitra->kontak_email }}">
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->kontak_email }}
                    </a>
                    <p class="mb-0 small"><span class="text-muted">Telepon:</span>
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->kontak_telepon }}
                    </p>
                </div>
                <hr class="my-2">
                <div class="d-flex flex-column gap-1 text-start">
                    <h6 class="fw-bold mb-0">Lokasi</h6>
                    <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                        target="_blank">
                        {{ $lokasi->alamat }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body d-flex flex-column gap-2 flex-fill">
        <div class="d-flex flex-column gap-1">
            <label for="catatan_dosen" class="form-label fw-bold">Catatan Dosen</label>
            <textarea class="form-control" id="catatan_dosen" name="catatan_dosen" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_dosen ?? '-' }}</textarea>
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
</div>
