@extends('layouts.app')

@section('content')
    @vite(['resources/js/import/tagify.js'])
    <form action="{{ route('admin.evaluasi.spk.profile-testing.update') }}"
        class="d-flex flex-row gap-4 pb-4 position-relative" id="form-profile" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="width: 334px; min-width: 334px"></div>
        <div class="d-flex flex-column text-start gap-3 position-fixed pb-5 z-1"
            style="top: 138px; max-height: calc(100vh - 118px); overflow-y: auto; width: 334px; min-width: 334px; max-width: 334px;">
            <h4 class="fw-bold mb-0">Edit Profil</h4>
            <div class="d-flex flex-column text-start align-items-center card p-3 position-relative"
                style="height: fit-content; max-width: 334px;">
                <div class="d-flex flex-row gap-3" style="min-width: 300px; max-width: 300px;">
                    <div for="profile_picture" class="position-relative"
                        style="width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
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
                        <p class="fw-bold mb-0"> <span class="text-muted">Angkatan: </span>{{ $user->angkatan }}</p>
                        <p class="fw-bold mb-0"> <span class="text-muted">IPK Komulatif: </span>{{ $user->ipk }}</p>
                    </div>

                </div>
                <label class="btn btn-primary mt-3 w-100" for="profile_picture">
                    Ganti Foto Profil
                </label>
                <input type="file" name="profile_picture" id="profile_picture" class="d-none"
                    accept="image/jpeg, image/jpg, image/png, image/webp"
                    onchange="this.parentNode.querySelector('#picture-display').src = window.URL.createObjectURL(this.files[0]);">
                <div id="error-profile_picture" class="text-danger small"></div>
                {{-- <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" /> --}}
                {{-- <button type="button" class="btn btn-danger w-100" id="btn-password">Ganti Password</button> --}}

            </div>
            <div class="d-flex flex-column text-start card p-3" style="height: fit-content;">
                <ul class="sidebar-nav pt-0" id="edit-nav">
                    <li class="nav-title p-0">Edit</li>
                    <div class="form-check form-switch d-flex justify-content-between p-0">
                        <label class="form-check-label" for="edit-show-all">Tampilkan Semua</label>
                        <input class="form-check-input" type="checkbox" id="edit-show-all">
                    </div>

                    <li class="nav-item" style="cursor: pointer;">
                        <a class="nav-link active" id="collapsePribadi">
                            <svg class="nav-icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contact') }}">
                                </use>
                            </svg> Informasi Pribadi
                        </a>
                    </li>
                    <li class="nav-item" style="cursor: pointer;">
                        <a class="nav-link" id="collapsePreferensi">
                            <svg class="nav-icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-factory') }}">
                                </use>
                            </svg> Preferensi Magang
                        </a>
                    </li>
                    <li class="nav-item" style="cursor: pointer;">
                        <a class="nav-link" id="collapseSkill">
                            <svg class="nav-icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-lightbulb') }}">
                                </use>
                            </svg> Keahlian
                        </a>
                    </li>
                    <li class="nav-item" style="cursor: pointer;">
                        <a class="nav-link" id="collapsePengalaman">
                            <svg class="nav-icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-airplane-mode') }}">
                                </use>
                            </svg> Pengalaman
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="d-flex flex-column gap-3 flex-fill">
            <div class="collapse" id="collapsePribadi">
                <div class="flex-column d-flex gap-3 pb-3">
                    <h4 class="fw-bold mb-0">Akademik</h4>
                    <div class="card w-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="number" class="form-control" id="angkatan" name="angkatan"
                                    value="{{ $user->angkatan }}">
                            </div>
                            <div class="mb-3">
                                <label for="ipk" class="form-label">IPK Komulatif</label>
                                <input type="number" class="form-control" id="ipk" name="ipk"
                                    value="{{ $user->ipk }}">
                            </div>
                        </div>
                    </div>
                </div>
                @include('mahasiswa.profile.edit-pribadi')
            </div>
            <div class="collapse" id="collapsePreferensi">
                @include('mahasiswa.profile.edit-preferensi')
            </div>
            <div class="collapse" id="collapseSkill">
                @include('mahasiswa.profile.edit-skill')
            </div>
            <div class="collapse" id="collapsePengalaman">
                @include('mahasiswa.profile.edit-pengalaman')
            </div>

            <div class="d-flex justify-content-start gap-2">
                <x-btn-submit-spinner size="22">
                    Simpan
                </x-btn-submit-spinner>
                <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
            </div>
        </div>
    </form>

    @include('components.location-picker')

    <x-modal-yes-no id="modal-passwd" dismiss="false" static="true" title="Ganti Password">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Simpan
            </x-btn-submit-spinner>
        </x-slot>
        <form action="{{ route('mahasiswa.profile.update-password') }}" method="POST" id="form-passwd">
            @csrf
            @method('PUT')
            <div class="modal-body d-flex flex-column gap-3">
                <div class="mb-3">
                    <h5 class="card-title mb-2">Password</h5>
                    <input type="password" class="form-control" value="" name="password" id="password" required>
                    <div id="error-password" class="text-danger"></div>
                </div>
                <div class="mb-3">
                    <h5 class="card-title mb-2">Konfirmasi Password</h5>
                    <input type="password" class="form-control" value="" name="password_confirm"
                        id="password_confirm" required>
                    <div id="error-password_confirm" class="text-danger"></div>
                </div>
            </div>
        </form>
    </x-modal-yes-no>

    <script>
        const run = () => {
            const skillLevels = @json(array_keys($tingkat_kemampuan));
            const skillTags = @json($keahlian->pluck('nama_keahlian')->toArray());
            const tagifyInstances = [];
            const selectedSkills = new Set();

            skillLevels.forEach(level => {
                const element = document.getElementById(`keahlian-${level}`);
                if (element) {
                    const tagify = new Tagify(element, {
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
                        enforceWhitelist: true,
                    });

                    const initialTags = tagify.value.map(tag => tag.value);
                    initialTags.forEach(skill => selectedSkills.add(skill));
                    tagifyInstances.push(tagify);
                    tagify.on('add', function(e) {
                        const skill = e.detail.data.value;
                        selectedSkills.add(skill);
                        updateAllWhitelists();
                    });
                    tagify.on('remove', function(e) {
                        const skill = e.detail.data.value;
                        selectedSkills.delete(skill);
                        updateAllWhitelists();
                    });
                }
            });

            const updateAllWhitelists = () => {
                tagifyInstances.forEach(instance => {
                    const currentTags = instance.value.map(tag => tag.value);
                    const currentTagSet = new Set(currentTags);
                    const availableSkills = skillTags.filter(skill =>
                        !selectedSkills.has(skill) || currentTagSet.has(skill)
                    );

                    //  update whitelist
                    instance.settings.whitelist = availableSkills;
                    instance.loading(true);
                    instance.dropdown.show.call(instance, availableSkills[0] || '');
                    instance.loading(false);
                });
            }
            updateAllWhitelists();

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
                                window.location.reload();
                            });
                        },
                        error: function(response) {
                            console.log(response.responseJSON);
                            btnSpinerFuncs.resetBtnSubmit(form);
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
            if (btnPasswd) {
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
            }

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


            const collapseMap = {
                collapsePribadi: new coreui.Collapse('.collapse#collapsePribadi', {
                    toggle: false
                }),
                collapsePreferensi: new coreui.Collapse('.collapse#collapsePreferensi', {
                    toggle: false
                }),
                collapseSkill: new coreui.Collapse('.collapse#collapseSkill', {
                    toggle: false
                }),
                collapsePengalaman: new coreui.Collapse('.collapse#collapsePengalaman', {
                    toggle: false
                })
            };

            collapseMap.collapsePribadi.show();
            const editShowAll = document.getElementById('edit-show-all');
            const editNav = document.getElementById('edit-nav');
            editShowAll.addEventListener('change', () => {
                if (editShowAll.checked) {
                    editNav.querySelectorAll('.nav-item a').forEach(a => {
                        a.classList.remove('active');
                        a.style.opacity = 0.5;
                        a.style.pointerEvents = 'none';
                    });
                    editNav.querySelectorAll('.nav-item').forEach(item => item.style.cursor = '');
                    Object.values(collapseMap).forEach(collapse => collapse.show());
                } else {
                    Object.values(collapseMap).forEach((collapse, key) => {
                        if (key !== 0) collapse.hide();
                    });
                    editNav.querySelectorAll('.nav-item a')[0].classList.add('active');
                    editNav.querySelectorAll('.nav-item a').forEach(a => {
                        a.style.opacity = '';
                        a.style.pointerEvents = '';
                    });
                    editNav.querySelectorAll('.nav-item').forEach(item => item.style.cursor = 'pointer');
                }
            });

            editNav.addEventListener('click', event => {
                if (document.querySelector('.collapsing') || editShowAll.checked) return;
                const alink = event.target.closest('a');
                if (!alink || alink.classList.contains('active')) return;

                Object.keys(collapseMap).forEach(key => {
                    if (alink.id === key) {
                        collapseMap[key].show();
                    } else {
                        collapseMap[key].hide();
                    }
                });

                editNav.querySelectorAll('.nav-item a').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('.collapse').forEach(collapse => collapse.classList.remove('show'));
                alink.classList.add('active');
            });
        }
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
