@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container">

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <strong class="fs-5">Detail Mahasiswa</strong>
        </div>

        <div class="card-body position-relative pt-3">
            {{-- Tombol kanan atas --}}
            <div class="position-absolute top-0 end-0 mt-2 me-2 d-flex flex-column gap-2">
                <a href="{{ route('dosen.mahasiswabimbingan') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('dosen.detail.logAktivitas', $pengajuan->pengajuan_id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-clock me-1"></i> Log Aktivitas
                </a>
            </div>

            <div class="d-flex flex-row gap-4 align-items-center">
                {{-- Foto Profil --}}
                <div class="position-relative" style="width: 100px; height: 100px; clip-path: circle(50% at 50% 50%);">
                    <img src="{{ $user->foto_profil ? asset( $user->foto_profil) : asset('imgs/profile_placeholder.jpg') }}?{{ now() }}"
                        alt="Foto Dosen" class="w-100" id="picture-display">

                    <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                        style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 0; left: 0;"
                        onmouseover="this.style.opacity = 0.5;"
                        onmouseout="this.style.opacity = 0;"
                        onclick="document.getElementById('full-screen-image').style.display = 'flex';
                             document.getElementById('picture-display-full').src = this.parentNode.querySelector('#picture-display').src;">
                        <svg class="position-absolute text-white h-auto"
                            style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20%">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}"></use>
                        </svg>
                    </div>
                </div>

                {{-- Gambar Fullscreen --}}
                <div id="full-screen-image"
                    class="position-fixed w-100 h-100 justify-content-center align-items-center"
                    style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                    onclick="this.style.display = 'none';">
                    <img id="picture-display-full" alt="Profile Picture"
                        class="img-fluid" style="max-width: 90%; max-height: 90%;">
                </div>

                {{-- Info Mahasiswa --}}
                <div class="d-flex flex-column">
                    <p class="fw-bold fs-5 mb-0">{{ $pengajuan->profilMahasiswa->nama }}</p>
                    <p class="text-muted fs-6 mb-1 ">{{ $pengajuan->profilMahasiswa->nim }}</p>
                    <p class="fs-6 mb-0"><strong>Prodi:</strong> {{ $pengajuan->profilMahasiswa->programStudi->nama_program ?? '-' }}</p>
                    <p class="fs-6 mb-0"><strong>Semester:</strong> {{ $pengajuan->profilMahasiswa->semester }}</p>
                    <p class="fs-6 mb-0"><strong>Kontak:</strong> {{ $pengajuan->profilMahasiswa->nomor_telepon ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>


    {{-- Card Detail Lowongan --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <strong>Detail Lowongan</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <th width="40%">Judul Lowongan</th>
                        <td>{{ $pengajuan->lowonganMagang->judul_posisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $pengajuan->lowonganMagang->lokasi->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Perusahaan</th>
                        <td>{{ $pengajuan->lowonganMagang->perusahaan->nama_perusahaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $pengajuan->lowonganMagang->deskripsi ?? '-' }}</td>
                    </tr>
                    
                    <tr>
                        <th>Email Perusahaan</th>
                        <td>{{ $pengajuan->lowonganMagang->perusahaan->kontak_email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon Perusahaan</th>
                        <td>{{ $pengajuan->lowonganMagang->perusahaan->kontak_telepon ?? '-' }}</td>
                    <tr>
                        <th>Tipe Kerja</th>
                        <td>{{ $pengajuan->lowonganMagang->tipe_kerja_lowongan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d-m-Y') }}</td>
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($pengajuan->status) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection