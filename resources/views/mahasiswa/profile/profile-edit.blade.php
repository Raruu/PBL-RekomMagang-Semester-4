@extends('layouts.app')

@section('content')
    @vite(['resources/js/import/tagify.js'])
    <form action="{{ url('/mahasiswa/profile/update') }}" class="d-flex flex-row gap-4 pb-4" id="form-profile" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column text-start gap-3">
            <h4 class="fw-bold mb-0">Edit Profil</h4>
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
                <label class="btn btn-primary mt-3 w-100" for="profile_picture">
                    Ganti Foto Profil
                </label>
                <input type="file" name="profile_picture" id="profile_picture" class="d-none"
                    accept="image/jpeg, image/jpg, image/png, image/webp"
                    onchange="this.parentNode.querySelector('#picture-display').src = window.URL.createObjectURL(this.files[0]);">
                <div id="error-profile_picture" class="text-danger small"></div>
                <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
                <button type="button" class="btn btn-danger w-100" id="btn-password">Ganti Password</button>

            </div>

            <h4 class="fw-bold mb-0">Keahlian</h4>
            <div class="d-flex flex-column text-start align-items-center card p-3" style="height: fit-content;">
                @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                    <div class="mb-3 w-100" style="max-width: 300px;">
                        <p class="mb-0 fw-bold">{{ $tingkatKemampuan }}</p>
                        <input type="text" class="form-control" name="keahlian-{{ $keytingkatKemampuan }}"
                            id="keahlian-{{ $keytingkatKemampuan }}"
                            value="{{ implode(', ', $keahlian_mahasiswa->where('tingkat_kemampuan', $keytingkatKemampuan)->pluck('keahlian.nama_keahlian')->toArray()) }}">
                        <div id="error-keahlian-{{ $keytingkatKemampuan }}" class="text-danger"></div>
                    </div>
                @endforeach
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

            <h4 class="fw-bold mb-0">Preferensi Magang</h4>
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
                        <input type="number" class="d-none" name="location_latitude" id="location_latitude" readonly
                            value="{{ $user->preferensiMahasiswa->lokasi->latitude }}">
                        <input type="number" class="d-none" name="location_longitude" id="location_longitude" readonly
                            value="{{ $user->preferensiMahasiswa->lokasi->longitude }}">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ $user->preferensiMahasiswa->lokasi->alamat }}" name="lokasi_alamat"
                                id="lokasi_alamat" required>
                            <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center"
                                type="button"
                                onClick="openLocationPicker((event)=>{
                                    document.getElementById('lokasi_alamat').value = 
                                        event.target.querySelector('#address-input').value;
                                    document.getElementById('location_latitude').value = 
                                        event.target.querySelector('#location-latitude').value;
                                    document.getElementById('location_longitude').value = 
                                        event.target.querySelector('#location-longitude').value;
                                }, document.getElementById('lokasi_alamat').value, 
                                {lat: {{ $user->preferensiMahasiswa->lokasi->latitude }},
                                 lng: {{ $user->preferensiMahasiswa->lokasi->longitude }}})">
                                <svg class="nav-icon" style="width: 20px; height: 20px;">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                                    </use>
                                </svg>
                            </button>
                        </div>
                        <div id="error-lokasi_alamat" class="text-danger"></div>
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
                        <select class="form-select" name="tipe_kerja_preferensi" id="tipe_kerja_preferensi" required>
                            @foreach ($tipe_kerja_preferensi as $value => $label)
                                <option value="{{ $value }}"
                                    {{ $user->preferensiMahasiswa->tipe_kerja_preferensi == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
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

    @include('components.page-modal')
    @include('components.location-picker')

    <div class="modal fade" id="modal-passwd" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/mahasiswa/profile/update-password') }}" method="POST" id="form-passwd">
                    @csrf
                    <div class="modal-body d-flex flex-column gap-3">
                        <div class="mb-3">
                            <h5 class="card-title mb-2">Password</h5>
                            <input type="password" class="form-control" value="" name="password" id="password"
                                required>
                            <div id="error-password" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <h5 class="card-title mb-2">Konfirmasi Password</h5>
                            <input type="password" class="form-control" value="" name="password_confirm"
                                id="password_confirm" required>
                            <div id="error-password_confirm" class="text-danger"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" data-coreui-dismiss="modal"
                            id="btn-true">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const run = () => {
            const skillLevels = @json(array_keys($tingkat_kemampuan));
            const skillTags = @json($keahlian->pluck('nama_keahlian')->toArray());
            skillLevels.forEach(level => {
                const element = document.getElementById(`keahlian-${level}`);
                if (element) {
                    tagify = new Tagify(element, {
                        whitelist: skillTags,
                        dropdown: {
                            position: "input",
                            maxItems: Infinity,
                            enabled: 0,
                        },
                        templates: {
                            dropdownItemNoMatch() {
                                return `Nothing Found`;
                            }
                        },
                        enforceWhitelist: true
                    });
                }
            });

            const modalElement = document.getElementById('page-modal');
            modalElement.addEventListener('hidden.coreui.modal', function(event) {
                const title = event.target.querySelector('.modal-title')?.textContent;
                const modalBody = modalElement.querySelector('.modal-body');
                if (title.includes('Berhasil') && !modalBody.querySelector('#no-redirect')) window.location
                    .href = "{{ url('/mahasiswa/profile') }}";
                modalBody.innerHTML = '';
            });

            const modalPasswd = document.getElementById('modal-passwd');
            const btnPasswd = document.getElementById('btn-password');
            btnPasswd.addEventListener('click', () => {
                const modal = new coreui.Modal(modalPasswd);
                modal.show();
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
                                modalTitle.innerHTML = response.status ?
                                    '<svg class="nav-icon text-success" style="max-width: 32px; max-height: 22px;"><use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-check-circle') }}"></use></svg> Berhasil' :
                                    '<svg class="nav-icon text-danger" style="max-width: 32px; max-height: 22px;"><use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-warning') }}"></use></svg> Gagal';
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

                $("#form-passwd").validate({
                    rules: {
                        password: {
                            minlength: 5,
                            maxlength: 255,
                            required: true
                        },
                        password_confirm: {
                            required: true,
                            equalTo: "#password"
                        }
                    },
                    submitHandler: function(form) {
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                const modal = new coreui.Modal(modalElement);
                                const modalTitle = modalElement.querySelector(
                                    '.modal-title')
                                modalTitle.innerHTML = response.status ?
                                    '<svg class="nav-icon text-success" style="max-width: 32px; max-height: 22px;"><use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-check-circle') }}"></use></svg> Berhasil' :
                                    '<svg class="nav-icon text-danger" style="max-width: 32px; max-height: 22px;"><use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-warning') }}"></use></svg> Gagal';
                                const modalBody = modalElement.querySelector(
                                    '.modal-body');
                                modalBody.textContent = response.message;
                                modalBody.innerHTML +=
                                    "<div class ='d-none' id='no-redirect'></div>";

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
                    errorElement: "span",
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.input-group').append(error);
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
