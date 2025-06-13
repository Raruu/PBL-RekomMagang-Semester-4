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

                <div class="card mt-3 w-100 perusahaan_info_2 d-none" style="height: fit-content;">
                    <div class="card-body d-flex flex-column flex-fill text-center">
                        <div class="d-flex flex-column gap-1 mt-1">
                            <div
                                class="d-flex flex-row gap-1 align-content-center justify-content-start pt-1 px-1 flex-wrap">
                                <svg class="icon my-auto ">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-clock') }}">
                                    </use>
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
                        <div class="d-flex flex-row gap-3 justify-content-around">
                            <div class="d-flex flex-column gap-1 text-start">
                                <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                                <p class="mb-0 small">
                                    {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->nama_perusahaan }}
                                </p>
                                <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                                    {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->bidangIndustri->nama }}
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
                                <p class="mb-0 small"><span class="text-muted">Alamat Perusahaan:<br /></span>
                                    <a class="mb-0 small" target="_blank"
                                        href="https://maps.google.com/?q={{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->latitude }},{{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->longitude }}">
                                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->alamat }}
                                    </a>
                                </p>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex flex-column gap-1 text-start">
                                <h6 class="fw-bold mb-0">Lokasi Magang</h6>
                                <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                                    target="_blank">
                                    {{ $lokasi->alamat }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card m-3 perusahaan_info_1" style="height: fit-content; max-width: 250px;">
            <div class="card-body d-flex flex-column flex-fill text-center">
                <div class="d-flex flex-column gap-1 mt-1">
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-start pt-1 px-1 flex-wrap">
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
                {{-- <h4 class="mb-0">
                    <span
                        class="badge bg-info mb-0  {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'bg-info' : 'bg-danger' }}">
                        {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'Rp. ' . $pengajuanMagang->lowonganMagang->gaji : 'Tidak ada gaji' }}
                    </span>
                </h4>
                <hr class="my-2"> --}}
                <div class="d-flex flex-column gap-1 text-start">
                    <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                    <p class="mb-0 small">
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->nama_perusahaan }}
                    </p>
                    <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                        {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->bidangIndustri->nama }}
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
                    <p class="mb-0 small"><span class="text-muted">Alamat Perusahaan:<br /></span>
                        <a class="mb-0 small" target="_blank"
                            href="https://maps.google.com/?q={{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->latitude }},{{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->longitude }}">
                            {{ $pengajuanMagang->lowonganMagang->perusahaanMitra->lokasi->alamat }}
                        </a>
                    </p>
                </div>
                <hr class="my-2">
                <div class="d-flex flex-column gap-1 text-start">
                    <h6 class="fw-bold mb-0">Lokasi Magang</h6>
                    <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                        target="_blank">
                        {{ $lokasi->alamat }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-modal-yes-no id="modal-catatan" dismiss="false" static="true" title="Berikan Catatan">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                <i class="fas fa-paper-plane"></i> Kirim
            </x-btn-submit-spinner>
        </x-slot>
        <x-slot name="btnFalse">
            <i class="fas fa-times"></i> Batal
        </x-slot>
        <form method="POST" action="{{ route('admin.magang.kegiatan.update.catatan', $pengajuanMagang->id) }}">
            @csrf
            @method('PUT')
            <div class="d-flex flex-column gap-1">
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuanMagang->pengajuan_id }}">
                <label for="catatan_admin" class="form-label fw-bold my-auto">Berikan Catatan</label>
                <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="4">{{ $pengajuanMagang->catatan_admin }}</textarea>
            </div>
        </form>
        <h6 class="mt-2 text-muted">*Catatan Admin akan Langsung dikirim ke Mahasiswa</h6>
    </x-modal-yes-no>

    <x-page-modal id="modal-pdf-preview" title="Preview Dokumen" class="modal-xl">
        <iframe class="pdf_preview" src="" width="100%" style="height: 70vh"></iframe>
    </x-page-modal>

    <div class="card-body d-flex flex-column gap-2 flex-fill dokumen_field">
        <div class="d-flex flex-column gap-1">
            <div class="d-flex flex-row gap-2 align-items-center">
                <button class="btn btn-outline-secondary btn-sm btn_catatan" type="button">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <label for="catatan_admin" class="form-label fw-bold my-auto">Catatan Admin</label>
            </div>
            <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_admin ?? '-' }}</textarea>
        </div>
        <div class="d-flex flex-column gap-1">
            <label for="catatan_mahasiswa" class="form-label fw-bold">Catatan Mahasiswa</label>
            <textarea class="form-control" id="catatan_mahasiswa" name="catatan_mahasiswa" rows="2" readonly disabled>{{ $pengajuanMagang->catatan_mahasiswa ?? '-' }}</textarea>
        </div>
        @if (!$pengajuanMagang->dokumenPengajuan->isEmpty())
            <p class="fw-bold mb-1">Dokumen Pendukung</p>
        @endif
        @foreach ($pengajuanMagang->dokumenPengajuan->sortBy('jenis_dokumen') as $dokumen)
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
</div>
