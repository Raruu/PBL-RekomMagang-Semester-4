@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <h4>Dashboard Mahasiswa</h4>
        <div class="card flex-row w-100 p-3 py-4 justify-content-around">
            <div class="d-flex flex-row gap-3 align-items-center ">
                <div for="profile_picture" class="position-relative"
                    style="min-width: 64px; width: 64px; height: 64px; clip-path: circle(50% at 50% 50%);">
                    <img src="{{ Auth::user()->getPhotoProfile() ? asset($user->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                        alt="Profile Picture" class="w-100" id="picture-display">
                    <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                        style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                        onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 0;"
                        onclick="document.getElementById('full-screen-image').style.display = 'flex';
                                document.getElementById('picture-display-full').src = this.parentNode.querySelector('#picture-display').src;">
                        <svg class="position-absolute text-white h-auto"
                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 15%">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}">
                            </use>
                        </svg>
                    </div>
                </div>
                <x-picture-display-full />
                <div class="d-flex flex-column gap-1">
                    <h5 class="fw-bold mb-0">Selamaat datang</h5>
                    <p class="mb-0">{{ $user->nama }}</p>
                </div>
            </div>
            @foreach ($metrikPengajuan as $key => $value)
                <div class="d-grid flex-column gap-0">
                    <h5 class="fw-bold mb-0 text-muted">{{ Str::ucfirst($key) }}</h5>
                    <p class="mb-0 fw-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex flex-column gap-2 pb-4">
        <h4>Kegiatan Magang</h4>
        <div class="card flex-column w-100 p-3 py-4 justify-content-around">
            @foreach ($kegiatanMagang as $kegiatan)
                <div class="card-body background-hoverable"
                    onclick="window.location.href = '{{ route('mahasiswa.magang.pengajuan.detail', ['pengajuan_id' => $kegiatan->pengajuan_id]) }}'">
                    <div class="d-flex flex-row justify-content-between align-items-start">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex flex-row gap-2">
                                <h6 class="fw-bold mb-0">{{ Str::ucfirst($kegiatan->lowonganMagang->judul_lowongan) }}</h6>
                                <span class="badge bg-primary my-auto">
                                    {{ Str::ucfirst($kegiatan->lowonganMagang->tipe_kerja_lowongan) }}
                                </span>
                            </div>
                            <p class="mb-0">{{ Str::ucfirst($kegiatan->lowonganMagang->deskripsi) }}</p>
                            <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                                @foreach ($kegiatan->lowonganMagang->keahlianLowongan as $keahlian)
                                    <span
                                        class="badge bg-secondary">{{ Str::ucfirst($keahlian->keahlian->nama_keahlian) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-1 justify-content-center" style="height: 64px; width: 64px">
                            <a href="{{ route('mahasiswa.magang.log-aktivitas', ['pengajuan_id' => $kegiatan->pengajuan_id]) }}"
                                class="btn btn-outline-primary d-flex flex-column justify-content-center align-items-center flex-fill">
                                <i class="fas fa-arrow-right"></i>
                                <p class="mb-0" style="font-size: 0.75rem">Aktivitas</p>
                            </a>
                        </div>
                    </div>
                </div>
                @if ($loop->index >= 3)
                    <div class="d-flex flex-column align-items-center text-muted">
                        <h1 class="mb-0">...</h1>
                        <a href="{{ route('mahasiswa.magang.pengajuan') }}" class="text-decoration-none">
                            <h4 class="mb-0 text-muted">Lihat Semua</h4>
                        </a>
                    </div>
                    @break
                @endif
                @if ($loop->last)
                    <div class="d-flex flex-column align-items-center text-muted">
                        <h1 class="mb-0">...</h1>
                        <h4 class="mb-0">Itu saja</h4>
                    </div>
                @endif
            @endforeach       
        </div>
    </div>
@endsection
