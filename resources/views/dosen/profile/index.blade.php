@extends('layouts.app')

@section('content')
    <div class="d-flex flex-row gap-4 pb-4">
        <div class="d-flex flex-column text-start gap-3">
            <h4 class="fw-bold mb-0">Profil Dosen</h4>
            <div class="d-flex flex-column text-start align-items-center card p-3" style="height: fit-content;">
                <div class="d-flex flex-row gap-3" style="min-width: 300px; max-width: 300px;">
                    <div class="position-relative"
                        style="min-width: 90px; width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
                        <img src="{{ $user->foto_profil ? asset($user->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                            alt="Foto Dosen" class="w-100" id="picture-display">
                        <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                            style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                            onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 0;"
                            onclick="document.getElementById('full-screen-image').style.display = 'flex';
                            document.getElementById('picture-display-full').src = this.parentNode.querySelector('#picture-display').src;">
                            <svg class="position-absolute text-white h-auto"
                                style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 15%">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}"></use>
                            </svg>
                        </div>
                    </div>
                    <div id="full-screen-image" class="position-fixed w-100 h-100 justify-content-center align-items-center"
                        style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                        onclick="this.style.display = 'none';">
                        <img id="picture-display-full" alt="Foto Dosen" class="img-fluid"
                            style="max-width: 90%; max-height: 90%;">
                    </div>
                    <div class="d-flex flex-column">
                        <p class="fw-bold mb-0">{{ $user->nama }}</p>
                        <p class="mb-0 text-muted">{{ $user->nip }}</p>
                        <p class="fw-bold mb-0">{{ $user->programStudi->nama_program }}</p>
                    </div>
                </div>
                <a href="{{ route('dosen.edit-profil') }}" class="btn btn-primary mt-3 w-100">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </a>

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


                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Alamat</h5>
                        <a href="https://maps.google.com/?q={{ $user->lokasi->latitude }},{{ $user->lokasi->longitude }}"
                            target="_blank">
                            <p class="card-text">{{ $user->lokasi->alamat }}</p>
                        </a>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Minat Penelitian</h5>
                        <p class="card-text">{{ $user->minat_penelitian }}</p>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Nomor Telepon</h5>
                        <p class="card-text">{{ $user->nomor_telepon }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
