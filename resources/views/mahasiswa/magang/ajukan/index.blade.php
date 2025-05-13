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

    <script>
        const run = () => {
            const nav = performance.getEntriesByType("navigation")[0];
            console.log(nav.type);
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
                // progressBar[2].style.width = '0%';
            };
            btnNext.onclick = () => {
                const activeIndex = [...carouselDiv.querySelectorAll('.carousel-item')].indexOf(
                    carouselDiv.querySelector('.active')
                );

                if (activeIndex >= 0) {
                    btnNext.disabled = {{ $user->file_cv ? 'false' : 'true' }};
                }

                if (activeIndex >= 2) {
                    // progressBar[2].style.width = '100%';
                    return;
                }

                changeStepTitle(activeIndex + 1);
                progressBar[activeIndex].style.width = '100%';
                carousel.next();
            };

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
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
