@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="card flex-row w-100">
            @include('mahasiswa.magang.detail-lowongan')

            <div class="card m-4" style="height: fit-content; max-width: 250px;">
                <div class="card-body d-flex flex-column flex-fill text-center">
                    @if ($pengajuanMagang)
                        <a href="{{ route('mahasiswa.magang.pengajuan.detail', ['pengajuan_id' => $pengajuanMagang]) }}"
                            class="btn btn-warning">
                            Lihat Pengajuan
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.magang.lowongan.ajukan', ['lowongan_id' => $lowongan->lowongan_id]) }}"
                            class="btn btn-primary">
                            Ajukan Magang
                        </a>
                    @endif
                    <hr class="my-2">
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
                            {{ $lowongan->perusahaanMitra->bidangIndustri->nama }}
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
                        <p class="mb-0 small"><span class="text-muted">Alamat Perusahaan:<br /></span>
                            <a class="mb-0 small" target="_blank"
                                href="https://maps.google.com/?q={{ $lowongan->perusahaanMitra->lokasi->latitude }},{{ $lowongan->perusahaanMitra->lokasi->longitude }}">
                                {{ $lowongan->perusahaanMitra->lokasi->alamat }}
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
                        <p class="mb-0 small"><span class="text-muted">Jarak dengan preferensi:<br /></span>
                            {{ number_format($jarak, 2) }} <span class="text-muted fw-bold">KM</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex p-1 flex-row w-100">
            <button type="button" class="btn btn-secondary"
                onclick="@if ($backable) window.history.back()
            @else
                window.location.href='{{ route('mahasiswa.magang') }}' @endif ">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
    </div>
@endsection
