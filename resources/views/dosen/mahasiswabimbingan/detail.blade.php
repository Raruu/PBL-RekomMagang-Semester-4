@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="container pb-3">
        <div class="card mb-3">
            <div class="card-body">

                {{-- Card Info Mahasiswa --}}
                <div class="card mb-3">
                    <div class="card-body position-relative pt-3">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <div class="d-flex flex-row gap-4 align-items-center">
                                {{-- Foto Profil --}}
                                <div class="position-relative"
                                    style="width: 100px; height: 100px; clip-path: circle(50% at 50% 50%);">
                                    <img src="{{ $pengajuan->profilMahasiswa->foto_profil ? asset($pengajuan->profilMahasiswa->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                                        alt="Foto Dosen" class="w-100 h-100 object-fit-cover" id="picture-display">

                                    <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                                        style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 0; left: 0;"
                                        onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 0;"
                                        onclick="document.getElementById('full-screen-image').style.display = 'flex';
                                    document.getElementById('picture-display-full').src = this.parentNode.querySelector('#picture-display').src;">
                                        <svg class="position-absolute text-white h-auto"
                                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20%">
                                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}">
                                            </use>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Gambar Fullscreen --}}
                                <div id="full-screen-image"
                                    class="position-fixed w-100 h-100 justify-content-center align-items-center"
                                    style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                                    onclick="this.style.display = 'none';">
                                    <img id="picture-display-full" alt="Profile Picture" class="img-fluid"
                                        style="max-width: 90%; max-height: 90%;">
                                </div>

                                {{-- Info Mahasiswa --}}
                                <div class="d-flex flex-column">
                                    <p class="fw-bold fs-5 mb-0">{{ $pengajuan->profilMahasiswa->nama }}</p>
                                    <p class="text-muted fs-6 mb-1">{{ $pengajuan->profilMahasiswa->nim }}</p>
                                    <p class="fs-6 mb-0"><strong>Prodi:</strong>
                                        {{ $pengajuan->profilMahasiswa->programStudi->nama_program ?? '-' }}</p>
                                    <p class="fs-6 mb-0"><strong>Angkatan:</strong>
                                        {{ $pengajuan->profilMahasiswa->angkatan ?? '-' }}</p>
                                    <p class="fs-6 mb-0"><strong>Kontak:</strong>
                                        {{ $pengajuan->profilMahasiswa->nomor_telepon ?? '-' }}</p>
                                    <p class="fs-6 mb-0"><strong>Alamat:</strong>
                                        <a href="https://maps.google.com/?q={{ $pengajuan->profilMahasiswa->lokasi->latitude }},{{ $pengajuan->profilMahasiswa->lokasi->longitude }}"
                                            target="_blank">
                                            {{ $pengajuan->profilMahasiswa->lokasi->alamat ?? 'Tidak tersedia' }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            {{-- Tombol kanan atas --}}
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('dosen.mahasiswabimbingan') }}" class="btn btn-md btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('dosen.detail.logAktivitas', $pengajuan->pengajuan_id) }}"
                                    class="btn btn-md btn-primary">
                                    <i class="fas fa-clock me-1"></i> Log Aktivitas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @include('dosen.mahasiswabimbingan.detail-lowongan')

                {{-- Card Info Pengajuan --}}
            </div>
        </div>
    </div>
@endsection
