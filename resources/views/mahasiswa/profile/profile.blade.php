@extends('layouts.app')
@section('content')
    <div class="d-flex flex-row gap-5 pb-4">
        <div class="d-flex flex-column gap-4 text-start align-items-center">
            <h1 class="mt-4 fw-bold">Profil<br />Mahasiswa</h1>
            <div for="profile_picture" class="position-relative"
                style="width: 190px; height: 190px; clip-path: circle(50% at 50% 50%);">
                <img src="{{ Auth::user()->getPhotoProfile() ? asset($user->foto_profil) : asset('imgs/profile_placeholder.jpg') }}?{{ now() }}"
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
            <div id="full-screen-image" class="position-fixed w-100 h-100 justify-content-center align-items-center"
                style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                onclick="this.style.display = 'none';">
                <img id="picture-display-full" alt="Profile Picture" class="img-fluid"
                    style="max-width: 90%; max-height: 90%;">
            </div>
            <a href="{{ url('/mahasiswa/profile/edit') }}" class="btn btn-primary">
                Edit Profil
            </a>

        </div>

        <div class="d-flex flex-column gap-3 flex-fill">
            <div class="d-flex flex-column gap-0">
                <p class="mb-0">
                    <span class="fw-bold" style="font-size: 1.5rem;">{{ $user->nama }}</span> &#8226;
                    <span class="my-auto"> {{ $user->nim }}</span>
                </p>
                <p class="card-text">{{ $user->programStudi->nama_program }} &#8226; Semester: {{ $user->semester }}</p>
            </div>
            <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-row gap-3 flex-fill">
                        <div class="flex-fill">
                            <div class="mb-3">
                                <h5 class="card-title">Email</h5>
                                <p class="card-text">{{ $user->user->email }}</p>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-3">
                                <h5 class="card-title">Nomor Telepon</h5>
                                <p class="card-text">{{ $user->nomor_telepon }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Alamat</h5>
                        <p class="card-text">{{ $user->alamat }}</p>
                    </div>
                </div>
            </div>

            <h4 class="fw-bold mb-0">Preferensi Magang</h4>
            <div class="card w-100">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="card-title">Industri</h5>
                        <p class="card-text">{{ $user->preferensiMahasiswa->industri_preferensi }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Lokasi</h5>
                        <p class="card-text">{{ $user->preferensiMahasiswa->lokasi_preferensi }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Posisi</h5>
                        <p class="card-text">{{ $user->preferensiMahasiswa->posisi_preferensi }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Tipe Kerja</h5>
                        <p class="card-text">{{ ucfirst($user->preferensiMahasiswa->tipe_kerja_preferensi) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
