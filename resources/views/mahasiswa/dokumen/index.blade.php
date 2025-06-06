@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column gap-0 align-items-center w-100 pb-5">
        <form action="{{ route('mahasiswa.dokumen.upload.cv') }}" id="form-dokumen-cv" method="POST" class="card mb-4 w-100"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>CV (Curriculum Vitae)</strong> &#8226; <span class="small">Max 2MB</span></span>
                <div class="d-flex flex-row gap-2">
                    <div class="d-flex flex-column gap-1 justify-content-center align-items-end">
                        <span class="badge bg-{{ $user->file_cv != null ? 'success' : 'danger' }}"
                            style="width: fit-content">
                            <i class="fas fa-{{ $user->file_cv != null ? 'check' : 'times' }}"></i>
                            {{ $user->file_transkrip_nilai != null ? 'Upload ' : 'Belum Upload' }}
                        </span>
                    </div>
                    <x-btn-submit-spinner disabled id="upload-button" size="22">
                        <svg class="icon">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-upload') }}"></use>
                        </svg>
                        Upload
                    </x-btn-submit-spinner>
                    <a href="{{ asset($user->file_cv) }}"
                        class="btn btn-outline-primary {{ $user->file_cv == null ? 'disabled' : '' }}" download>
                        <svg class="icon">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-download') }}"></use>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input class="form-control" id="dokumen_cv" name="dokumen_cv" type="file" accept=".pdf">
                </div>
            </div>

            <div class="accordion" id="accordion-cv">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                            data-coreui-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <p class="mb-0">
                                <strong>Klik untuk </strong> melihat CV Anda
                            </p>
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-coreui-parent="#accordion-cv">
                        <div class="accordion-body">
                            <h1 class="{{ $user->file_cv ? 'd-none' : '' }} text-center">Belum ada dokumen</h1>
                            <iframe id="iframe-dokumen-cv" class="w-100 {{ $user->file_cv ? '' : 'd-none' }}"
                                style="height: calc(100vh - 200px);"
                                src="{{ $user->file_cv ? asset($user->file_cv) : '' }}" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if ($on_complete == null)
            <form action="{{ route('mahasiswa.dokumen.upload.transkripNilai') }}" id="form-transkrip-nilai" method="POST"
                class="card mb-4 w-100" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Transkrip Nilai</strong> &#8226; <span class="small"><strong>SETELAH UPLOAD MAKA
                                HARUS MENUNGGU VERIFIKASI</strong>, Max 2MB</span></span>
                    <div class="d-flex flex-row gap-2">
                        <div class="d-flex flex-column gap-1 justify-content-center align-items-end">
                            <span class="badge bg-{{ $user->file_transkrip_nilai != null ? 'success' : 'danger' }}"
                                style="width: fit-content">
                                <i class="fas fa-{{ $user->file_transkrip_nilai != null ? 'check' : 'times' }}"></i>
                                {{ $user->file_transkrip_nilai != null ? 'Upload ' : 'Belum Upload' }}
                            </span>
                            <span class="badge bg-{{ $user->verified ? 'success' : 'danger' }} "
                                style="width: fit-content">
                                <i class="fas fa-{{ $user->verified ? 'check' : 'times' }}"></i>
                                {{ $user->verified ? 'Diverifikasi ' : 'Belum Diverifikasi' }}
                            </span>
                        </div>
                        <button type="button" disabled id="upload-button" class="btn btn-danger my-auto"
                            style="height: fit-content">
                            <svg class="icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-upload') }}"></use>
                            </svg>
                            Upload
                        </button>
                        <a href="{{ asset($user->file_transkrip_nilai) }}" style="height: fit-content"
                            class="btn btn-outline-primary {{ $user->file_transkrip_nilai == null ? 'disabled' : '' }} my-auto"
                            download>
                            <svg class="icon">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-download') }}">
                                </use>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <div class="mb-3">
                        <input class="form-control" id="dokumen_transkrip_nilai" name="dokumen_transkrip_nilai"
                            type="file" accept=".xlsx">
                    </div>
                    <div>
                        <a href="{{ asset('templates/transkrip_nilai.xlsx') }}" style="height: fit-content"
                            class="btn btn-outline-success my-auto" download>
                            <i class="fas fa-file-excel me-2"></i>
                            Unduh Template
                        </a>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <x-modal-yes-no dismiss="false" static="true" id="modal-confirm-nilai" title="Yakin Upload Transkrip Nilai?">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" class="btn btn-danger" wrapWithButton="false">
                <i class="fas fa-paper-plane me-2"></i> Kirim
            </x-btn-submit-spinner>
        </x-slot>
        <h6>SETELAH UPLOAD MAKA HARUS MENUNGGU VERIFIKASI</h6>
    </x-modal-yes-no>

    <script>
        const run = () => {
            const initCV = () => {
                const formDokumenCV = document.querySelector('#form-dokumen-cv');
                const uploadButton = formDokumenCV.querySelector('#upload-button');
                uploadButton.onclick = () => {
                    uploadButton.querySelector('#btn-submit-text').classList.add('d-none');
                    uploadButton.querySelector('#btn-submit-spinner').classList.remove('d-none');
                };

                const resetSpinner = () => {
                    uploadButton.querySelector('#btn-submit-text').classList.remove('d-none');
                    uploadButton.querySelector('#btn-submit-spinner').classList.add('d-none');
                };

                $("#form-dokumen-cv").validate({
                    submitHandler: function(form) {
                        const data = new FormData(form);
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    {!! $on_complete ? "window.location.href = '" . e($on_complete) . "';" : 'window.location.reload();' !!}
                                });
                            },
                            error: function(response) {
                                console.log(response.responseJSON);
                                resetSpinner();
                                Swal.fire({
                                    title: `Gagal!`,
                                    text: response.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
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

                const iframe = formDokumenCV.querySelector('#iframe-dokumen-cv');
                const dokumen_cv = formDokumenCV.querySelector('#dokumen_cv');
                dokumen_cv.addEventListener('change', function() {
                    if (this.files[0].size > 2 * 1024 * 1024) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'File tidak boleh lebih dari 2MB',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        dokumen_cv.value = '';
                        return;
                    }

                    const disabled = !this.files.length;
                    uploadButton.disabled = disabled;
                    if (disabled) {
                        uploadButton.classList.add('btn-secondary');
                        uploadButton.classList.remove('btn-primary');
                    } else {
                        uploadButton.classList.add('btn-primary');
                        uploadButton.classList.remove('btn-secondary');
                    }
                });
                dokumen_cv.addEventListener('change', () => {
                    const file = dokumen_cv.files[0];
                    const fileUrl = URL.createObjectURL(file);
                    iframe.src = fileUrl;
                    iframe.classList.remove('d-none');
                    iframe.parentNode.querySelector('h1').classList.add('d-none');
                    const accordionButton = formDokumenCV.querySelector('#accordion-cv').querySelector(
                        '.accordion-button');
                    if (accordionButton.classList.contains('collapsed')) accordionButton.click();
                });
            };
            initCV();

            @if ($on_complete == null)
                const initTranskripNilai = () => {
                    const formDokumenTranskripNilai = document.querySelector('#form-transkrip-nilai');
                    const uploadButton = formDokumenTranskripNilai.querySelector('#upload-button');
                    uploadButton.onclick = () => {
                        const modalElement = document.querySelector('#modal-confirm-nilai');
                        const modal = new coreui.Modal(modalElement);
                        const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                        btnTrue.classList.add('btn-danger');
                        const btnFalse = modalElement.querySelector('#btn-false-yes-no');

                        btnTrue.onclick = () => {
                            btnSpinerFuncs.spinBtnSubmit(modalElement);
                            const data = new FormData(formDokumenTranskripNilai);
                            $.ajax({
                                url: formDokumenTranskripNilai.action,
                                type: formDokumenTranskripNilai.method,
                                data: data,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        btnSpinerFuncs.resetBtnSubmit(modalElement);
                                        window.location.reload();
                                    });
                                },
                                error: function(response) {
                                    console.log(response.responseJSON);
                                    Swal.fire({
                                        title: `Gagal!`,
                                        text: response.responseJSON.message,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    btnSpinerFuncs.resetBtnSubmit(modalElement);
                                }
                            });
                        };

                        btnFalse.onclick = () => {
                            modal.hide();
                        };
                        modal.show();
                    };

                    // const iframe = formDokumenTranskripNilai.querySelector('#iframe-dokumen-transkrip-nilai');
                    const dokumen_transkrip_nilai = formDokumenTranskripNilai.querySelector(
                        '#dokumen_transkrip_nilai');
                    dokumen_transkrip_nilai.addEventListener('change', function() {
                        if (this.files[0].size > 2 * 1024 * 1024) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'File tidak boleh lebih dari 2MB',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            dokumen_transkrip_nilai.value = '';
                            return;
                        }

                        const disabled = !this.files.length;
                        uploadButton.disabled = disabled;
                        if (disabled) {
                            uploadButton.classList.add('btn-secondary');
                            uploadButton.classList.remove('btn-primary');
                        } else {
                            uploadButton.classList.add('btn-primary');
                            uploadButton.classList.remove('btn-secondary');
                        }
                    });
                    dokumen_transkrip_nilai.addEventListener('change', () => {
                        const file = dokumen_transkrip_nilai.files[0];
                        // const fileUrl = URL.createObjectURL(file);
                        // iframe.src = fileUrl;
                        // iframe.classList.remove('d-none');
                        // iframe.parentNode.querySelector('h1').classList.add('d-none');
                        // const accordionButton = formDokumenTranskripNilai.querySelector('#accordion-transkrip-nilai').querySelector(
                        //     '.accordion-button');
                        // if (accordionButton.classList.contains('collapsed')) accordionButton.click();
                    });
                }
                initTranskripNilai();
            @endif
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
