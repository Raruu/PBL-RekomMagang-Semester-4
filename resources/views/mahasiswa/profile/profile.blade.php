@extends('layouts.app')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4">
        <div class="d-flex flex-column text-start gap-3">
            <h4 class="fw-bold mb-0">Profil Mahasiswa</h4>
            <div class="d-flex flex-column text-start align-items-center card p-3" style="height: fit-content;">
                <div class="d-flex flex-row gap-3" style="min-width: 300px; max-width: 300px;">
                    <div for="profile_picture" class="position-relative"
                        style="width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
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
                    <div class="d-flex flex-column">
                        <p class="fw-bold mb-0" style="font-weight: 500;">{{ $user->nama }}</p>
                        <p class="mb-0 text-muted">{{ $user->nim }}</p>
                        <p class="fw-bold mb-0">{{ $user->programStudi->nama_program }}</p>
                        <p class="fw-bold mb-0"> <span class="text-muted">Semester: </span>{{ $user->semester }}</p>

                    </div>

                </div>
                <a href="{{ url('/mahasiswa/profile/edit') }}" class="btn btn-primary mt-3 w-100">
                    Edit Profil
                </a>
                <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
                <div class="d-flex flex-column w-100">
                    <h5 class="fw-bold mb-2">Keahlian</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                            <div class="d-flex flex-column">
                                <p class="fw-bold mb-0"> &#8226; <span>{{ $tingkatKemampuan }}</span> </p>
                                <div class="d-flex flex-row gap-2 flex-wrap ps-2 _keahlian">
                                    @foreach ($keahlian_mahasiswa as $keahlianMahasiswa)
                                        @if ($keahlianMahasiswa->tingkat_kemampuan == $keytingkatKemampuan)
                                            <span
                                                class="badge badge-sm 
                                            @if ($keytingkatKemampuan == 'ahli') bg-danger 
                                            @elseif ($keytingkatKemampuan == 'mahir') bg-warning 
                                            @elseif ($keytingkatKemampuan == 'menengah') bg-primary 
                                            @else bg-info @endif">{{ $keahlianMahasiswa->keahlian->nama_keahlian }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 flex-fill">
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
                        <p class="card-text">{{ $user->preferensiMahasiswa->lokasi->alamat }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Posisi</h5>
                        <p class="card-text">{{ $user->preferensiMahasiswa->posisi_preferensi }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Tipe Kerja</h5>
                        <p class="card-text">
                            {{ $tipe_kerja_preferensi[$user->preferensiMahasiswa->tipe_kerja_preferensi] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const keahlianElement = document.querySelectorAll('._keahlian');
        keahlianElement.forEach(element => {
            if (element.children.length === 0) {
                element.innerHTML = '<p class="mb-0 text-muted fw-bold small">Tidak ada</p>';
            }
        });
    </script>
@endsection
