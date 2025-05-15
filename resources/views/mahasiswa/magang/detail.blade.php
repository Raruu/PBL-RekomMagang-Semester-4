@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="card flex-row w-100">
            @include('mahasiswa.magang.detail-lowongan')

            <div class="card m-4" style="height: fit-content">
                <div class="card-body d-flex flex-column flex-fill text-center">
                    @if ($pengajuanMagang)
                        <a href="{{ url('/mahasiswa/magang/pengajuan/' . $pengajuanMagang) }}"
                            class="btn btn-warning">
                            Lihat Pengajuan
                        </a>
                    @else
                        <a href="{{ url('/mahasiswa/magang/lowongan/' . $lowongan->lowongan_id . '/ajukan') }}"
                            class="btn btn-primary">
                            Ajukan Magang
                        </a>
                    @endif
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
        <div class="d-flex p-1 flex-row w-100">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
    </div>
@endsection
