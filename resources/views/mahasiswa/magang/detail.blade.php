@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4">
        <div class="card flex-row w-100">
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
                        <strong>
                            {{ date_diff(date_create($lowongan->batas_pendaftaran), date_create(date('Y-m-d')))->format('%a') }}</strong>
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
                                $keahlianLowongan = $lowongan->keahlianLowongan->where(
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
                    <div class="d-flex flex-row gap-2">
                        <div class="d-flex flex-row gap-1 align-content-center justify-content-center">
                            <svg class="icon my-auto ">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}"></use>
                            </svg>
                            <span class="badge bg-primary my-auto">
                                {{ ucfirst($lowongan->tipe_kerja_lowongan) }}
                            </span>
                        </div>

                        <div class="d-flex flex-row gap-1 align-content-center justify-content-center">
                            <p class="mb-0">%</p>
                            <span
                                class="badge fw-bold my-auto bg-{{ $score > 0.7 ? 'success' : ($score > 0.5 ? 'warning' : 'danger') }}">
                                {{ $score }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card m-4" style="height: fit-content">
                <div class="card-body d-flex flex-column flex-fill text-center">
                    <button type="button" class="btn btn-primary">
                        Ajukan Magang
                    </button>
                    <hr class="my-2">
                    <h4>
                        <span class="badge bg-info mb-0  {{ $lowongan->gaji > 0 ? 'bg-info' : 'bg-danger' }}">
                            {{ $lowongan->gaji > 0 ? 'Rp. ' . $lowongan->gaji : 'Tidak ada gaji' }}
                        </span>
                    </h4>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1 text-start">
                        <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                        <p class="mb-0 small">
                            {{ $lowongan->perusahaan->nama_perusahaan }}
                        </p>
                        <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                            {{ $lowongan->perusahaan->bidang_industri }}
                        </p>

                        <a class="mb-0 small" target="_blank" href="{{ $lowongan->perusahaan->website }}">
                            {{ $lowongan->perusahaan->website }}
                        </a>
                        <a class="mb-0 small" href="mailto:{{ $lowongan->perusahaan->kontak_email }}">
                            {{ $lowongan->perusahaan->kontak_email }}
                        </a>
                        <p class="mb-0 small"><span class="text-muted">Telepon:</span>
                            {{ $lowongan->perusahaan->kontak_telepon }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
