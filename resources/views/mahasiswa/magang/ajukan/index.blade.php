@extends('layouts.app')
@section('title', 'Ajukan Magang')
@section('content-top')
    <style>
        #setup-progress {
            pointer-events: none;
        }

        #setup-progress .progress {
            height: 8px;
        }

        #drop-zone {
            border: 2px dashed #6c757d;
            border-radius: 0.375rem;
            padding: 2rem;
            transition: background-color 0.3s;
        }

        #drop-zone:hover,
        #drop-zone.dragover {
            background-color: var(--background-hover);
        }
    </style>
    <div class="d-flex flex-column gap-4 flex-fill">
        <div id="carousel" class="carousel slide flex-fill d-flex">
            <div class="carousel-inner flex-fill">
                <div class="carousel-item active h-100">
                    @include('mahasiswa.magang.ajukan.page-1')
                </div>
                <div class="carousel-item">
                    @include('mahasiswa.magang.ajukan.page-2')
                </div>
                <div class="carousel-item">
                    @include('mahasiswa.magang.ajukan.page-3')
                </div>
            </div>
        </div>
    </div>

@endsection
@section('content-bottom')
    <div style="background-color: var(--background); " class="d-flex flex-column gap-1">
        <div class="d-flex flex-row gap-1" id="setup-progress">
            <div class="progress flex-fill">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="progress flex-fill">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="progress flex-fill">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
        <div class="d-flex flex-row gap-1 justify-content-between align-items-center p-3">
            <button type="button" class="btn btn-secondary" id="btn-prev">
                Kembali
            </button>
            <div class="text-center" id="step-title">
                <h4 class="fw-bold mb-0"></h4>
                <p class="text-body-secondary mb-0"></p>
            </div>
            <button type="button" class="btn btn-primary" id="btn-next">
                Selanjutnya
            </button>
        </div>
    </div>
    <x-modal-yes-no id="modal-yes-no" dismiss=false btnTrue="<span id='btn-submit-text'>Ya</span>">
        Dengan ini, Anda melakukan ajukan magang ke perusahaan
        <strong>{{ $lowongan->perusahaan->nama_perusahaan }}</strong>
    </x-modal-yes-no>

    <script>
        const initDropZone = () => {
            const dropZone = document.querySelector('#drop-zone');
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });


            dropZone.addEventListener('drop', (event) => {
                event.preventDefault();
                dropZone.classList.remove('dragover');
                const files = event.dataTransfer.files;
                if (files.length) {
                    const origin = event.target.querySelector('input[type="file"]');
                    origin.files = files;
                    addFileTambahan(origin);
                }
            });
            return dropZone;
        };

        const run = () => {
            const nav = performance.getEntriesByType("navigation")[0];
            if (nav.type === "back_forward") {
                window.location.reload();
            }

            const dokumenInput = document.getElementById('dokumen_input[]');
            const fileInputGroup = document.querySelector('#file-input-group');

            const addFileTambahan = (origin) => {
                const newDiv = document.createElement('div');
                newDiv.classList.add('d-flex', 'flex-row', 'gap-2');
                newDiv.innerHTML = `@include('mahasiswa.magang.ajukan.dokumen-tambahan')`;
                fileInputGroup.appendChild(newDiv);

                const target = fileInputGroup.lastElementChild;
                target.appendChild(origin.cloneNode(true));
                target.querySelector('#file_name').value = origin.files[0].name;
                target.querySelector('#button-preview-file').onclick = () => {
                    window.open(URL.createObjectURL(target.lastElementChild.files[0]));
                };
                const errorField = document.createElement('div');
                errorField.id = `error-dokumen_input[]`;
                errorField.classList.add('text-danger');
                target.appendChild(errorField);
            };

            dokumenInput.addEventListener('change', (event) => {
                addFileTambahan(event.target);
            });

            const carouselDiv = document.querySelector('#carousel');
            const carousel = new coreui.Carousel(carouselDiv, {
                interval: false,
                wrap: false,
            });

            const progressBar = document.querySelectorAll('.progress-bar');

            const btnPrev = document.querySelector('#btn-prev');
            const btnNext = document.querySelector('#btn-next');
            const stepTitle = document.querySelector('#step-title');
            const textsTitle = ['Data Diri', 'Dokumen Tambahan', 'Konfirmasi'];
            const textsSubtitle = ['Data sudah benar?', 'Ada dokumen tambahan ?', 'Semua sudah benar?'];
            const changeStepTitle = (index) => {
                stepTitle.querySelector('h4').innerHTML =
                    `<span class="text-muted">Langkah ${index + 1}:</span> ${textsTitle[index]}`;
                stepTitle.querySelector('p').textContent = textsSubtitle[index];
            };
            changeStepTitle(0);

            const modalConfirmElement = document.getElementById('modal-yes-no');
            const modalConfirm = new coreui.Modal(modalConfirmElement);

            const btnModalTrue = modalConfirmElement.querySelector('#btn-true-yes-no');
            const btnModalFalse = modalConfirmElement.querySelector('#btn-false-yes-no');

            const dropZone = initDropZone();

            const confirmCancel = () => {
                progressBar[2].style.width = '0%';
                dropZone.querySelector('input[type="file"]').disabled = false;
                btnModalTrue.querySelector('#btn-submit-text').classList.remove('d-none');
                btnModalTrue.querySelector('#btn-submit-spinner').classList.add('d-none');
                btnModalTrue.disabled = false;
                btnModalFalse.disabled = false;
            };

            btnModalTrue.appendChild(document.createElement('div')).outerHTML = `<x-btn-submit-spinner size="22"/>`;
            btnModalTrue.onclick = () => {
                dropZone.querySelector('input[type="file"]').disabled = true;
                btnModalTrue.querySelector('#btn-submit-text').classList.add('d-none');
                btnModalTrue.querySelector('#btn-submit-spinner').classList.remove('d-none');
                btnModalFalse.disabled = true;
                btnModalTrue.disabled = true;
                const form = document.querySelector('#form-ajukan');
                fetch(form.action, {
                        method: form.method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href =
                                    "{{ url('/mahasiswa/magang') }}";
                            });
                        } else {
                            console.log(data);
                            Swal.fire('Gagal!', data.message, 'error');
                            confirmCancel();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        Swal.fire('Gagal!', error.message, 'error');
                        confirmCancel();
                    });
            };

            btnModalFalse.onclick = () => {
                confirmCancel();
                modalConfirm.hide();
            };

            btnPrev.onclick = () => {
                carousel.prev();
                const activeIndex = [...carouselDiv.querySelectorAll('.carousel-item')].indexOf(
                    carouselDiv.querySelector('.active')
                );
                btnNext.disabled = false;
                if (activeIndex == 0) {
                    window.history.back();
                    return;
                }
                changeStepTitle(activeIndex - 1);
                progressBar[activeIndex - 1].style.width = '0%';
            };
            btnNext.onclick = () => {
                const activeIndex = [...carouselDiv.querySelectorAll('.carousel-item')].indexOf(
                    carouselDiv.querySelector('.active')
                );

                if (activeIndex >= 0) {
                    btnNext.disabled = {{ $user->file_cv ? 'false' : 'true' }};
                }

                if (activeIndex >= 1) {
                    const requiredFields = ['dosen_id', 'jenis_dokumen[]'];
                    let isValid = true;
                    const form = document.querySelector('#form-ajukan');
                    requiredFields.forEach(fieldName => {
                        const field = form.querySelector(`[name="${fieldName}"]`);
                        const errorElement = document.querySelector(
                            `#error-${fieldName.replace('[]', '')}`);
                        if (field && !field.checkValidity()) {
                            field.classList.add('is-invalid');
                            if (errorElement)
                                errorElement.innerHTML = 'Field ini tidak boleh kosong';
                            isValid = false;
                        } else if (field) {
                            field.classList.remove('is-invalid');
                            if (errorElement)
                                errorElement.innerHTML = '';
                        }
                    });

                    form.querySelectorAll('input[type="file"]').forEach(field => {
                        const errorElement = field.parentElement.querySelector(
                            `#error-${field.name.replace('[]', '')}`);
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        const isInvalid = !field.checkValidity() || (field.files[0] && field.files[0].size >
                            maxSize);
                        if (isInvalid) {
                            field.classList.add('is-invalid');
                            if (errorElement)
                                errorElement.innerHTML = field.files[0] && field.files[0].size > maxSize ?
                                'File tidak boleh lebih dari 2MB' : 'Field ini tidak boleh kosong';
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                            if (errorElement)
                                errorElement.innerHTML = '';
                        }
                    });
                    if (!isValid)
                        return;
                }

                if (activeIndex >= 2) {
                    progressBar[2].style.width = '100%';
                    modalConfirm.show();
                    return;
                }

                changeStepTitle(activeIndex + 1);
                progressBar[activeIndex].style.width = '100%';
                carousel.next();
            };
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
