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
                <button type="submit" class="btn btn-primary">
                    <span id="btn-submit-text">Simpan</span>
                    @include('components.btn-submit-spinner')
                </button>
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
            const btnSpiner = document.getElementById('btn-submit-spinner');
            btnSpiner.style.width = "22px";
            btnSpiner.style.height = "22px";
            const btnSubmitText = document.getElementById('btn-submit-text');
            modalElement.addEventListener('hidden.coreui.modal', function(event) {
                btnSubmitText.classList.remove('d-none');
                btnSpiner.classList.add('d-none');
                btnSpiner.closest('button').disabled = false;

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
