@extends('layouts.app')

@section('content')
@vite(['resources/js/import/tagify.js'])

<form action="{{ url('/dosen/profile/update') }}" method="POST" enctype="multipart/form-data" id="form-profile"
    class="d-flex flex-row gap-4 pb-4 position-relative">
    @csrf

    {{-- Sidebar kiri --}}
    <div style="width: 334px; min-width: 334px"></div>
    <div class="d-flex flex-column text-start gap-3 position-fixed pb-5"
        style="top: 138px; z-index: 1036; max-height: calc(100vh - 118px); overflow-y: auto;">

        <h4 class="fw-bold mb-0">Edit Profil</h4>

        {{-- Foto Profil --}}
        <div class="d-flex flex-column text-start align-items-center card p-3"
                style="height: fit-content; max-width: 334px;">
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
                    <p class="fw-bold mb-0">{{ $user->nama }}</p>
                    <p class="text-muted mb-0">{{ $user->nip }}</p>
                    <p class="fw-bold mb-0">{{ $user->programStudi->nama_program  }}
                    </p>
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

    {{-- Fullscreen Image Preview --}}
    <div id="full-screen-image" class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
        onclick="this.style.display = 'none';">
        <img id="picture-display-full" alt="Profile Picture" class="img-fluid"
            style="max-width: 90%; max-height: 90%;">
    </div>

    {{-- Form Bagian Utama --}}
    <div class="d-flex flex-column gap-3 flex-fill">
        <div class="collapse show" id="collapsePribadi">
            @include('dosen.profile.edit-pribadi')
        </div>

        <div class="d-flex gap-2">
            <x-btn-submit-spinner size="22">
                Simpan
            </x-btn-submit-spinner>
            <button type="reset" class="btn btn-secondary"
                onclick="window.location.href='{{ url('/dosen/profile') }}'">Batal</button>
        </div>
    </div>
</form>

{{-- Komponen Tambahan --}}
@include('components.page-modal')
@include('components.location-picker')

{{-- Modal Ganti Password --}}
<div class="modal fade" id="modal-passwd" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/dosen/profile/update-password') }}" method="POST" id="form-passwd">
                @csrf
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                        <div id="error-password" class="text-danger"></div>
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                            required>
                        <div id="error-password_confirm" class="text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-coreui-dismiss="modal" id="btn-true">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

        document.getElementById('btn-password').addEventListener('click', () => {
            const modal = new coreui.Modal(document.getElementById('modal-passwd'));
            modal.show();
        });

         $(document).ready(function() {
                $("#form-profile").validate({
                    submitHandler: function(form) {
                        btnSpiner.closest('button').disabled = true;
                        btnSubmitText.classList.add('d-none');
                        btnSpiner.classList.remove('d-none');
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
        });
    };
    document.addEventListener('DOMContentLoaded', run);
</script>
@endsection