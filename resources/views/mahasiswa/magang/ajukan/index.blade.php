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
    <div class="d-flex flex-column gap-4 flex-fill pt-3" style="max-height: calc(100vh - 210px);">
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
    <x-modal-yes-no id="modal-yes-no" dismiss="false" static="true">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Ajukan
            </x-btn-submit-spinner>
        </x-slot>
        Dengan ini, Anda melakukan ajukan magang ke perusahaan<br />
        <strong>{{ $lowongan->perusahaanMitra->nama_perusahaan }}</strong>
    </x-modal-yes-no>

    <x-page-modal id="modal-pdf-preview" title="Preview Dokumen" class="modal-xl">
        <iframe class="pdf_preview" src="" width="100%" style="height: 70vh"></iframe>
    </x-page-modal>

    <script>
        const addDokumenByPersyaratan = (element) => {
            const fileInput = document.getElementById('dokumen_input[]');
            const documentName = element.parentElement.querySelector('.dokumen_persyaratan_name').textContent;
            fileInput.setAttribute('data-documentName', documentName);
            fileInput.click();
        };

        const notifyDocumentChanged = () => {
            const documentPersyaratan = document.querySelectorAll('.dokumen_persyaratan_name');
            const jenisDokumen = document.querySelectorAll('input[name="jenis_dokumen[]"]');
            documentPersyaratan.forEach(element => {
                const parent = element.parentElement.parentElement.parentElement;
                const checkBox = parent.querySelector('.dokumen_persyaratan');
                checkBox.checked = false;
                parent.querySelector('.btn-outline-primary').classList.remove('d-none');
            });
            jenisDokumen.forEach(element => {
                documentPersyaratan.forEach(persyaratan => {
                    if (persyaratan.textContent === element.value) {
                        const parent = persyaratan.parentElement.parentElement.parentElement;
                        const checkBox = parent.querySelector('.dokumen_persyaratan');
                        checkBox.checked = true;
                        parent.querySelector('.btn-outline-primary').classList.add('d-none');
                    }
                });
            });
        };

        const initDropZone = (addFileTambahan) => {
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
                for (let i = 0; i < files.length; i++) {
                    const origin = dropZone.querySelector('input[type="file"]');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[i]);
                    console.log(dataTransfer.files);
                    origin.files = dataTransfer.files;
                    addFileTambahan(origin, null);
                }
            });
            return dropZone;
        };

        const run = () => {
            const checkDokumenPersyaratanEmpty = () => {
                const dokumenPersyaratan = document.querySelector('.dokumen_persyaratan_container');
                if (dokumenPersyaratan.childElementCount == 0) {
                    dokumenPersyaratan.previousElementSibling.remove();
                    dokumenPersyaratan.remove();
                }
            };
            checkDokumenPersyaratanEmpty();

            const nav = performance.getEntriesByType("navigation")[0];
            if (nav.type === "back_forward") {
                window.location.reload();
            }

            const dokumenInput = document.getElementById('dokumen_input[]');
            const fileInputGroup = document.querySelector('#file-input-group');

            const addFileTambahan = (origin, documentName) => {
                const newDiv = document.createElement('div');
                newDiv.classList.add('d-flex', 'flex-row', 'gap-2');
                newDiv.innerHTML = `@include('mahasiswa.magang.ajukan.dokumen-tambahan')`;
                fileInputGroup.appendChild(newDiv);

                const target = fileInputGroup.lastElementChild;
                const documentNameInput = target.querySelector('input[name="jenis_dokumen[]"]');
                if (documentName !== null) {
                    documentNameInput.value = documentName;
                } else {
                    documentNameInput.value = `Dokumen ke-${fileInputGroup.children.length}`;
                }

                documentNameInput.addEventListener('blur', (event) => {
                    documentNameInput.value = documentNameInput.value.trim();
                    notifyDocumentChanged();
                });

                target.appendChild(origin.cloneNode(true));
                target.querySelector('.file_name').value = origin.files[0].name;
                target.querySelector('.button_preview_file').onclick = () => {
                    const modalElement = document.querySelector('#modal-pdf-preview');
                    modalElement.querySelector('.modal-title').textContent =
                        `Preview Dokumen: ${target.querySelector('input[name="jenis_dokumen[]"]').value}`;
                    const modal = new coreui.Modal(modalElement);
                    const iframe = modalElement.querySelector('.pdf_preview');
                    iframe.src = URL.createObjectURL(target.querySelector('input[type="file"]').files[0]);
                    modal.show();
                };
                const errorField = document.createElement('div');
                errorField.id = `error-dokumen_input[]`;
                errorField.classList.add('text-danger');
                target.appendChild(errorField);
            };

            dokumenInput.addEventListener('click', (event) => {
                if (event.target.classList.contains('btn-outline-danger')) {
                    event.target.parentElement.remove();
                }
            });

            dokumenInput.addEventListener('change', (event) => {
                const documentName = event.target.getAttribute('data-documentName');
                if (event.target.files && event.target.files.length > 0) {
                    addFileTambahan(event.target, documentName);
                    notifyDocumentChanged();
                }
                event.target.removeAttribute('data-documentName');
                event.target.value = '';
            });

            dokumenInput.addEventListener('cancel', (event) => {
                event.target.removeAttribute('data-documentName');
                event.target.value = '';
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
            const textsSubtitle = ['Data sudah benar?', 'Ada dokumen tambahan ?',
                'Anda dengan kesadaran melakukan pengajuan ke magang ini...'
            ];
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

            const dropZone = initDropZone(addFileTambahan);

            const confirmCancel = () => {
                progressBar[2].style.width = '0%';
                dropZone.querySelector('input[type="file"]').disabled = false;
                btnModalTrue.querySelector('#btn-submit-text').classList.remove('d-none');
                btnModalTrue.querySelector('#btn-submit-spinner').classList.add('d-none');
                btnModalTrue.disabled = false;
                btnModalFalse.disabled = false;
            };


            btnModalTrue.onclick = () => {
                dropZone.querySelector('input[type="file"]').disabled = true;
                btnModalTrue.querySelector('#btn-submit-text').classList.add('d-none');
                btnModalTrue.querySelector('#btn-submit-spinner').classList.remove('d-none');
                btnModalFalse.disabled = true;
                btnModalTrue.disabled = true;
                const form = document.querySelector('#form-ajukan');
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
                            window.location.href =
                                "{{ route('mahasiswa.magang.lowongan.detail', ['lowongan_id' => $lowongan->lowongan_id]) }}";
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
                        confirmCancel();
                    }
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
                    @if ($page)
                        window.location.href =
                            "{{ route('mahasiswa.magang.lowongan.detail', ['lowongan_id' => $lowongan->lowongan_id]) }}";
                    @else
                        window.history.back();
                    @endif

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
                    const requiredFields = ['jenis_dokumen[]'];
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

                    const dokumenPersyaratan = document.querySelector('.dokumen_persyaratan_container');
                    if (dokumenPersyaratan) {
                        dokumenPersyaratan.querySelectorAll('.dokumen_persyaratan').forEach(element => {
                            if (!element.checked) {
                                dokumenPersyaratan.style.setProperty('background-color',
                                    'rgba(255, 0, 0, 0.59)', 'important');
                                setTimeout(() => {
                                    dokumenPersyaratan.style.backgroundColor = '';
                                }, 1000);
                                isValid = false;
                            }
                        });
                    }

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

            const openAtPage = (page) => {
                const activeIndex = [...carouselDiv.querySelectorAll('.carousel-item')].indexOf(
                    carouselDiv.querySelector('.active')
                );
                if (activeIndex > page) {
                    for (let i = 0; i < activeIndex - page; i++) {
                        btnPrev.click();
                    }
                } else {
                    for (let i = 0; i < page - activeIndex; i++) {
                        btnNext.click();
                    }
                }
            };
            @if ($page)
                openAtPage({{ $page }} - 1);
            @endif
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
