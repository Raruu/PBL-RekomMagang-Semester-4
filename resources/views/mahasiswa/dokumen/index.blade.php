@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column gap-0 align-items-center w-100 h-100">
        <form action="{{ url('/mahasiswa/dokumen/upload') }}" id="form-dokumen" method="POST" class="card mb-4 w-100"
            enctype="multipart/form-data">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><strong>CV Anda</strong> &#8226; <span class="small">Upload lagi untuk mengupdate</span></span>
                <div class="d-flex flex-row gap-2">
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
        </form>
        <div class="w-100 h-100 d-flex flex-column align-items-center">
            <iframe id="iframe-dokumen-cv" class="w-100 h-100 {{ $user->file_cv ? '' : 'd-none' }}"
                src="{{ $user->file_cv ? asset($user->file_cv) : '' }}" allowfullscreen>
            </iframe>

            <h1 class="{{ $user->file_cv ? 'd-none' : '' }}">Belum ada dokumen</h1>
        </div>
    </div>

    <script>
        const run = () => {
            const uploadButton = document.getElementById('upload-button');
            document.getElementById('dokumen_cv').addEventListener('change', function() {
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

            uploadButton.onclick = () => {
                uploadButton.querySelector('#btn-submit-text').classList.add('d-none');
                uploadButton.querySelector('#btn-submit-spinner').classList.remove('d-none');
            };

            const resetSpinner = () => {
                uploadButton.querySelector('#btn-submit-text').classList.remove('d-none');
                uploadButton.querySelector('#btn-submit-spinner').classList.add('d-none');
            };

            $("#form-dokumen").validate({
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                {!! $on_complete ? "window.location.href = '" . e($on_complete) . "';" : "window.location.reload();" !!}
                            });
                        },
                        error: function(response) {
                            console.log(response.responseJSON);
                            resetSpinner();
                            Swal.fire({
                                title: `Gagal ${response.status}`,
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

            const iframe = document.getElementById('iframe-dokumen-cv');
            document.getElementById('dokumen_cv').addEventListener('change', () => {
                const file = document.getElementById('dokumen_cv').files[0];
                const fileUrl = URL.createObjectURL(file);
                iframe.src = fileUrl;
                iframe.classList.remove('d-none');
                iframe.parentNode.querySelector('h1').classList.add('d-none');
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
