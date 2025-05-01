@extends('layouts.app')
@section('content')
    <form action="{{ url('/mahasiswa/profile/update') }}" class="d-flex flex-row gap-5 pb-4" id="form-profile" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column gap-4 text-start align-items-center">
            <h1 class="mt-4 fw-bold">Edit Profil<br />Mahasiswa</h1>
            <div for="profile_picture" class="position-relative"
                style="width: 190px; height: 190px; clip-path: circle(50% at 50% 50%);">
                <img src="{{ asset($user->foto_profil) ?? asset('imgs/profile_placeholder.jpg') }}?{{ now() }}"
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
            <label class="btn btn-primary" for="profile_picture">
                Ganti Foto Profil
            </label>
            <input type="file" name="profile_picture" id="profile_picture" class="d-none"
                accept="image/jpeg, image/jpg, image/png, image/webp"
                onchange="this.parentNode.querySelector('#picture-display').src = window.URL.createObjectURL(this.files[0]);">
            <div id="error-profile_picture" class="text-danger" style="max-width: 190px;"></div>

        </div>

        <div class="d-flex flex-column gap-3 flex-fill">
            <div class="d-flex flex-column gap-0">
                <p class="mb-0">
                    <span class="fw-bold" style="font-size: 1.5rem;">{{ $user->nama_lengkap }}</span> &#8226;
                    <span class="my-auto"> {{ $user->nim }}</span>
                </p>
                <p class="card-text">{{ $user->programStudi->nama_program }} &#8226; Semester: {{ $user->semester }}</p>
            </div>
            <h3 class="fw-bold mb-0">Informasi Pribadi</h3>
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-row gap-3 flex-fill">
                        <div class="flex-fill">
                            <div class="mb-3">
                                <h5 class="card-title">Email</h5>
                                <input type="email" class="form-control" value="{{ $user->user->email }}" name="email"
                                    id="email" required>
                                <div id="error-email" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-3">
                                <h5 class="card-title">Nomor Telepon</h5>
                                <input type="number" class="form-control" value="{{ $user->nomor_telepon }}"
                                    name="nomor_telepon" id="nomor_telepon" required>
                                <div id="error-nomor_telepon" class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Alamat</h5>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $user->alamat }}" name="alamat"
                                id="alamat" required>
                            <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center"
                                type="button"
                                onClick="openLocationPicker((event)=>{
                                    document.getElementById('alamat').value = 
                                        event.target.querySelector('#address-input').value;
                                }, document.getElementById('alamat').value)">
                                <svg class="nav-icon" style="width: 20px; height: 20px;">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                                    </use>
                                </svg>
                            </button>
                        </div>
                        <div id="error-alamat" class="text-danger"></div>
                    </div>
                </div>
            </div>

            <h3 class="fw-bold mb-0">Preferensi Magang</h3>
            <div class="card w-100">
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="card-title">Industri</h5>
                        <input type="text" class="form-control"
                            value="{{ $user->preferensiMahasiswa->industri_preferensi }}" name="industri_preferensi"
                            id="industri_preferensi" required>
                        <div id="error-industri_preferensi" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Lokasi</h5>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ $user->preferensiMahasiswa->lokasi_preferensi }}" name="lokasi_preferensi"
                                id="lokasi_preferensi" required>
                            <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center"
                                type="button"
                                onClick="openLocationPicker((event)=>{
                                    document.getElementById('lokasi_preferensi').value = 
                                        event.target.querySelector('#address-input').value;
                                }, document.getElementById('lokasi_preferensi').value)">
                                <svg class="nav-icon" style="width: 20px; height: 20px;">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                                    </use>
                                </svg>
                            </button>
                        </div>
                        <div id="error-lokasi_preferensi" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Posisi</h5>
                        <input type="text" class="form-control"
                            value="{{ $user->preferensiMahasiswa->posisi_preferensi }}" name="posisi_preferensi"
                            id="posisi_preferensi" required>
                        <div id="error-posisi_preferensi" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <h5 class="card-title">Tipe Kerja</h5>
                        <input type="text" class="form-control" readonly
                            value="{{ $user->preferensiMahasiswa->tipe_kerja_preferensi }}" name="tipe_kerja_preferensi"
                            id="tipe_kerja_preferensi" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-secondary"
                    onclick="window.location.href = '{{ url('/mahasiswa/profile') }}'">Batal</button>
            </div>
        </div>
    </form>

    <div class="modal fade" id="page-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('components.location-picker')


    <script>
        const run = () => {
            const modalElement = document.getElementById('page-modal');
            modalElement.addEventListener('hidden.coreui.modal', function(event) {
                const title = event.target.querySelector('.modal-title')?.textContent;
                if (title === 'Berhasil') window.location.href = "{{ url('/mahasiswa/profile') }}";
            });
            $(document).ready(function() {
                $("#form-profile").validate({
                    submitHandler: function(form) {
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: new FormData(form),
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                const modal = new coreui.Modal(modalElement);
                                const modalTitle = modalElement.querySelector(
                                    '.modal-title')
                                modalTitle.textContent = response.status ?
                                    'Berhasil' : 'Gagal';
                                modalElement.querySelector('.modal-body')
                                    .textContent = response.message;

                                if (!response.status) {
                                    console.log(response);
                                    let errorMsg = '\n';
                                    $.each(response.msgField, function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                        errorMsg += val[0] + '\n';
                                    });
                                    modalElement.querySelector('.modal-body')
                                        .innerHTML += errorMsg.replace(/\n/g, '<br>');
                                }

                                modal.show();
                            }
                        });
                        return false;
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        }
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
