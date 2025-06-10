@extends('layouts.app')

@section('content')
    @vite(['resources/js/import/tagify.js'])

    <form action="{{ route('dosen.update-profil') }}" method="POST" enctype="multipart/form-data" id="form-profile"
        class="d-flex flex-row gap-4 pb-4 position-relative main_form">
        @csrf

        {{-- Sidebar kiri --}}
        <div style="">
            <div class="d-flex flex-column text-start gap-3 sticky-top pb-5 width-334 info_left_wrapper">
                <h4 class="fw-bold mb-0">Edit Profil</h4>
                {{-- Foto Profil --}}
                <div class="d-flex flex-column text-start align-items-center card p-3" style="height: fit-content;">
                    <div class="d-flex flex-row gap-3" style="min-width: 300px; max-width: 300px;">
                        <div for="profile_picture" class="position-relative"
                            style="min-width: 90px; width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
                            <img src="{{ Auth::user()->getPhotoProfile() ? asset($user->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                                alt="Profile Picture" class="w-100 h-100 object-fit-cover" id="picture-display">
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
                        <div id="full-screen-image"
                            class="position-fixed w-100 h-100 justify-content-center align-items-center"
                            style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                            onclick="this.style.display = 'none';">
                            <img id="picture-display-full" alt="Profile Picture" class="img-fluid"
                                style="max-width: 90%; max-height: 90%;">
                        </div>
                        <div class="d-flex flex-column">
                            <p class="fw-bold mb-0">{{ $user->nama }}</p>
                            <p class="text-muted mb-0">{{ $user->nip }}</p>
                            <p class="fw-bold mb-0">{{ $user->programStudi->nama_program }}
                            </p>
                        </div>

                    </div>
                    <label class="btn btn-primary mt-3 w-100" for="profile_picture">
                        <i class="fas fa-edit me-2"></i> Ganti Foto Profil
                    </label>
                    <input type="file" name="profile_picture" id="profile_picture" class="d-none"
                        accept="image/jpeg, image/jpg, image/png, image/webp"
                        onchange="this.parentNode.querySelector('#picture-display').src = window.URL.createObjectURL(this.files[0]);">
                    <div id="error-profile_picture" class="text-danger small"></div>
                    <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
                    <button type="button" class="btn btn-danger w-100" id="btn-password"><i class="fas fa-key me-2"></i>
                        Ganti
                        Password</button>

                </div>

                {{-- Navigasi Edit --}}
                <div class="card p-3">
                    <ul class="sidebar-nav pt-0" id="edit-nav">
                        <li class="nav-item" style="cursor: pointer;">
                            <a class="nav-link active" id="collapsePribadi">
                                <svg class="nav-icon">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contact') }}"></use>
                                </svg>
                                Informasi Pribadi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Fullscreen Image Preview --}}
        <div id="full-screen-image" class="position-fixed w-100 h-100 justify-content-center align-items-center"
            style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
            onclick="this.style.display = 'none';">
            <img id="picture-display-full" alt="Profile Picture" class="img-fluid" style="max-width: 90%; max-height: 90%;">
        </div>

        {{-- Form Bagian Utama --}}
        <div class="d-flex flex-column gap-3 flex-fill">
            <div class="collapse show" id="collapsePribadi">
                @include('dosen.profile.edit-pribadi')
            </div>

            <div class="d-flex gap-2">
                <x-btn-submit-spinner size="22">
                    <i class="fas fa-save"></i> Simpan
                </x-btn-submit-spinner>
                <button type="reset" class="btn btn-secondary"
                    onclick="window.location.href='{{ url('/dosen/profile') }}'"><i class="fas fa-arrow-left"></i>
                    Kembali</button>
            </div>
        </div>
    </form>

    {{-- Komponen Tambahan --}}
    @include('components.page-modal')
    @include('components.location-picker')

    {{-- Modal Ganti Password --}}
    <x-modal-yes-no id="modal-passwd" dismiss="false" static="true" title="Ganti Password">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                <i class="fas fa-save"></i> Simpan
            </x-btn-submit-spinner>
        </x-slot>
        <x-slot name="btnFalse">
            <i class="fas fa-times"></i> Batal
        </x-slot>
        <form action="{{ route('dosen.profile.update-password') }}" method="POST" id="form-passwd">
            @csrf
            <div class="modal-body d-flex flex-column gap-3">
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                    <div id="error-password" class="text-danger"></div>
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm" required>
                    <div id="error-password_confirm" class="text-danger"></div>
                </div>
            </div>
        </form>
    </x-modal-yes-no>

    {{-- Script --}}
    <script>
        const run = () => {
            const modalElement = document.getElementById('page-modal');
            const btnSpiner = document.getElementById('btn-submit-spinner');
            const btnSubmitText = document.getElementById('btn-submit-text');

            btnSpiner.style.width = "22px";
            btnSpiner.style.height = "22px";

            modalElement.addEventListener('hidden.coreui.modal', function(event) {
                btnSubmitText.classList.remove('d-none');
                btnSpiner.classList.add('d-none');
                btnSpiner.closest('button').disabled = false;

                const title = event.target.querySelector('.modal-title')?.textContent;
                const modalBody = modalElement.querySelector('.modal-body');
                if (title?.includes('Berhasil') && !modalBody.querySelector('#no-redirect')) {
                    window.location.href = "{{ url('/dosen/profile') }}";
                }
                modalBody.innerHTML = '';
            });

            $(document).ready(function() {
                $("#form-profile").validate({
                    submitHandler: function(form) {
                        btnSpinerFuncs.spinBtnSubmit(form);
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: new FormData(form),
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href =
                                        `{{ $on_complete ? e($on_complete) : route('dosen.profile') }}`;
                                });
                            },
                            error: function(response) {
                                console.log(response.responseJSON);
                                btnSpinerFuncs.resetBtnSubmit(form);
                                Swal.fire({
                                    title: `Gagal!`,
                                    text: response.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                $.each(response.responseJSON.msgField,
                                    function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
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
                const modalPasswd = document.getElementById('modal-passwd');
                const btnPasswd = document.getElementById('btn-password');
                btnPasswd.onclick = () => {
                    const modal = new coreui.Modal(modalPasswd);
                    const btnFalse = modalPasswd.querySelector('#btn-false-yes-no');
                    const btnTrue = modalPasswd.querySelector('#btn-true-yes-no');
                    btnFalse.onclick = () => {
                        modal.hide();
                    };
                    btnTrue.onclick = () => {
                        const form = document.getElementById('form-passwd');
                        btnSpinerFuncs.spinBtnSubmit(modalPasswd);
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                modalPasswd.querySelector('form').reset();
                                modal.hide();
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                btnSpinerFuncs.resetBtnSubmit(modalPasswd);
                            },
                            error: function(response) {
                                console.log(response.responseJSON);
                                btnSpinerFuncs.resetBtnSubmit(modalPasswd);
                                Swal.fire({
                                    title: `Gagal ${response.status}`,
                                    text: response.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                $.each(response.responseJSON.msgField,
                                    function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
                            }
                        });
                    };
                    modal.show();
                };

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

            document.addEventListener('mediaQueryChange', (event) => {
                const result = event.detail;
                const mainForm = document.querySelector('.main_form');
                const infoLeftWrapper = document.querySelector('.info_left_wrapper');
                if (!mainForm || !infoLeftWrapper) return;
                switch (result) {
                    case 'xs':
                    case 'sm':
                        mainForm.classList.remove('flex-row');
                        infoLeftWrapper.classList.remove('width-334');
                        mainForm.classList.add('flex-column');
                        break;
                    default:
                        mainForm.classList.remove('flex-column');
                        mainForm.classList.add('flex-row');
                        infoLeftWrapper.classList.add('width-334');
                        break;
                }
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
