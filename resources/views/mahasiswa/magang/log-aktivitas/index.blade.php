@extends('layouts.app')
@section('title', 'Log Aktivitas Mahasiswa')

@push('styles')
    @vite(['resources/css/timeline.css'])
@endpush

@section('content-top')
    <style>
        .timeline-nav {
            background-color: var(--cui-tertiary-bg);
            position: sticky;
            top: 113px;
        }

        [data-coreui-theme=dark] .timeline-nav {
            background-color: var(--cui-dark-bg-subtle);
        }

        .btn-up {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            pointer-events: none;
        }
    </style>
    <div class="z-3 w-100 timeline-nav">
        <div class="d-flex flex-column text-start gap-3 pb-2 container-lg px-4">
            <div class="d-flex p-1 flex-row w-100 justify-content-between">
                <div type="button" class="mt-2 d-flex flex-row gap-2 align-items-center"
                    onclick="window.location.href='{{ route('mahasiswa.magang.pengajuan.detail', ['pengajuan_id' => $pengajuanMagang->pengajuan_id]) }}';">
                    <i class="fas fa-arrow-left"></i>
                    <h4 class="fw-bold mb-0">Log Aktivitas</h4>
                </div>

                <div class="d-flex flex-row gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-up" onclick="goToTop();">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="fetchData()">
                        <x-btn-submit-spinner size="22" wrapWithButton="false">
                            <i class="fas fa-sync-alt"></i> Reload
                        </x-btn-submit-spinner>
                    </button>
                    @if ($pengajuanMagang->status == 'selesai')
                        <a href="{{ route('mahasiswa.magang.log-aktivitas.excel', ['pengajuan_id' => $pengajuanMagang->pengajuan_id]) }}"
                            class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    @else
                        <button type="button" class="btn btn-primary" onclick="add()">
                            <i class="fas fa-plus"></i> Tambah Log
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="position-relative mt-3">
        <div class="timeline position-relative">
            <div class="timeline-line"></div>
            <div id="timeline-container"></div>
        </div>
        <div class="text-center d-flex flex-column align-items-center pb-5" id="timeline-footer">
            <div class="" id="footer-loading">
                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"></div>
                <p>Fetching data...</p>
            </div>
            <div class="d-none p-5" id="footer-iceberg">
                <img src="{{ asset('imgs/sanhua-froze.webp') }}" alt="ice" style="width: 8rem">
                <h2><i><b>Itu saja</b></i></h2>
            </div>
        </div>
    </div>
    <x-page-modal id="modal-show">
        <div class="d-flex flex-column gap-2">
            <div>
                <h6>Aktivitas</h6>
                <h5 id="aktivitas"></h5>
            </div>

            <div>
                <h6>Kendala</h6>
                <p class="mb-0" id="kendala"></p>
            </div>
            <div>
                <h6>Solusi</h6>
                <p class="mb-0" id="solusi"></p>
            </div>
            <div>
                <h6>Feedback Dosen</h6>
                <textarea id="feedback_dosen" class="form-control" rows="3" readonly disabled></textarea>
            </div>
            <div class="d-flex flex-row justify-content-start gap-2 mt-3">
                <div class="d-flex flex-column">
                    <h6>Tanggal Log</h6>
                    <p class="mb-0" id="tanggal_log"></p>
                </div>
                <div class="d-flex flex-column">
                    <h6>Jam Kegiatan</h6>
                    <p class="mb-0" id="jam_kegiatan"></p>
                </div>
            </div>
        </div>
    </x-page-modal>

    @if ($pengajuanMagang->status != 'selesai')
        <x-modal-yes-no id="modal-edit" dismiss="false" static="true">
            <x-slot name="btnTrue">
                <x-btn-submit-spinner size="22" wrapWithButton="false">
                    Simpan
                </x-btn-submit-spinner>
            </x-slot>
            <form action="{{ route('mahasiswa.magang.log-aktivitas.update', ['pengajuan_id' => ':id']) }}" method="POST"
                class="d-flex flex-column gap-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="log_id" id="log_id" class="form-control">
                <label for="aktivitas">Aktivitas</label>
                <input type="text" name="aktivitas" id="aktivitas" class="form-control" required>
                <div id="error-aktivitas" class="text-danger"></div>
                <label for="kendala">Kendala</label>
                <input type="text" name="kendala" id="kendala" class="form-control">
                <div id="error-kendala" class="text-danger"></div>
                <label for="solusi">Solusi</label>
                <input type="text" name="solusi" id="solusi" class="form-control">
                <div id="error-solusi" class="text-danger"></div>
                <label for="tanggal_log">Tanggal Log</label>
                <input type="date" name="tanggal_log" id="tanggal_log" class="form-control" required>
                <div id="error-tanggal_log" class="text-danger"></div>
                <label for="jam_kegiatan">Jam Kegiatan</label>
                <input type="time" name="jam_kegiatan" id="jam_kegiatan" class="form-control" step="1"
                    required>
                <div id="error-jam_kegiatan" class="text-danger"></div>
            </form>
        </x-modal-yes-no>
    @endif

    <script>
        const goToTop = () => {
            const contentMid = document.querySelector('#content-mid');
            contentMid.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };

        const fetchData = async () => {
            const timeLineNav = document.querySelector('.timeline-nav');
            btnSpinerFuncs.spinBtnSubmit(timeLineNav);
            const timeLineContainer = document.querySelector('#timeline-container');
            const timeLineFooter = document.querySelector('#timeline-footer');
            timeLineContainer.innerHTML = '';
            timeLineFooter.querySelector('#footer-loading').classList.remove('d-none');
            timeLineFooter.querySelector('#footer-iceberg').classList.add('d-none');
            const response = await fetch(
                '{{ route('mahasiswa.magang.log-aktivitas.data', ['pengajuan_id' => $pengajuan_id]) }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            const data = (await response.json()).data;
            Object.keys(data).sort((a, b) => b.localeCompare(a)).forEach(key => {
                timeLineContainer.innerHTML +=
                    `<div class="timeline-item"><div class="timeline-marker info"></div><div class="fw-bold" id="log-${key}">${new Intl.DateTimeFormat('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(new Date(key))}</div></div>`;
                data[key].sort((a, b) => b.jam_kegiatan.localeCompare(a.jam_kegiatan)).forEach(log => {
                    const hasAdditionalInfo = log.kendala || log.solusi || log.feedback_dosen;
                    timeLineContainer.innerHTML += `@include('mahasiswa.magang.log-aktivitas.timeline-sub')`;
                });
            });

            timeLineFooter.querySelector('#footer-loading').classList.add('d-none');
            timeLineFooter.querySelector('#footer-iceberg').classList.remove('d-none');
            btnSpinerFuncs.resetBtnSubmit(timeLineNav);
        };

        @if ($pengajuanMagang->status != 'selesai')
            const resetForm = () => {
                const modalElement = document.querySelector('#modal-edit');
                const form = document.querySelector('#modal-edit form');
                form.reset();
                form.querySelector('input[name="log_id"]').value = '';
                const requiredFields = ['aktivitas', 'tanggal_log', 'jam_kegiatan'];
                requiredFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    const errorElement = document.querySelector(
                        `#error-${fieldName}`);
                    if (field) {
                        field.classList.remove('is-invalid');
                        if (errorElement)
                            errorElement.innerHTML = '';
                    }
                });
            };

            const editCancle = (modal) => {
                resetForm();
                modal.hide();
            };

            const submitEditForm = (form, modal) => {
                const requiredFields = ['aktivitas', 'tanggal_log', 'jam_kegiatan'];
                let isValid = true;
                requiredFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    const errorElement = document.querySelector(
                        `#error-${fieldName}`);
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
                if (!isValid)
                    return;

                const modalElement = document.querySelector('#modal-edit');
                btnSpinerFuncs.spinBtnSubmit(modalElement);
                const data = new FormData(form);
                for (const pair of data.entries()) {
                    if (typeof pair[1] === 'string')
                        data.set(pair[0], sanitizeString(pair[1]));
                }
                data.append('pengajuan_id', '{{ $pengajuan_id }}');
                const logId = data.get('log_id') == '' ? 'new' : data.get('log_id');

                $.ajax({
                    url: form.action.replace(':id', logId),
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
                            modal.hide();
                            resetForm();
                            const url = new URL(window.location.href);
                            const oldHref = url.href;
                            fetchData().then(() => {
                                setTimeout(() => {
                                    const contentMid = document.querySelector(
                                        '#content-mid');
                                    contentMid.scrollTo({
                                        top: document.querySelector(
                                                `#log-${data.get('tanggal_log')}`
                                            )
                                            .getBoundingClientRect()
                                            .top +
                                            window.pageYOffset - 118,
                                        behavior: 'smooth'
                                    });
                                }, 50);
                            });
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
                        $.each(response.responseJSON.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    },
                    complete: function() {
                        btnSpinerFuncs.resetBtnSubmit(modalElement);
                    }
                });
            };

            const add = () => {
                const modalElement = document.querySelector('#modal-edit');
                modalElement.querySelector('.modal-title').textContent = 'Tambah Log Aktivitas';
                const modal = new coreui.Modal(modalElement);
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');
                const form = document.querySelector('#modal-edit form');

                btnTrue.onclick = () => {
                    submitEditForm(form, modal);
                };
                btnFalse.onclick = () => {
                    editCancle(modal);
                };

                modal.show();
            };

            const edit = (element) => {
                const target = element.parentElement.parentElement;
                const log_id = target.querySelector('div[name=log_id]').textContent;
                const kendala = target.querySelector('div[name=kendala]').textContent;
                const solusi = target.querySelector('div[name=solusi]').textContent;
                const feedback_dosen = target.querySelector('div[name=feedback_dosen]').textContent;
                const tanggal_log = target.querySelector('div[name=tanggal_log]').textContent;
                const jam_kegiatan = target.querySelector('div[name=jam_kegiatan]').textContent;
                const aktivitas = target.querySelector('div[name=aktivitas]').textContent.trim().replace(
                    /^\s+|\s+$/g,
                    '');
                const form = document.querySelector('#modal-edit form');
                form.querySelector('input[name=log_id]').value = log_id === '' ? '' : log_id;
                form.querySelector('input[name=kendala]').value = kendala === '-' ? '' : kendala;
                form.querySelector('input[name=solusi]').value = solusi === '-' ? '' : solusi;
                form.querySelector('input[name=tanggal_log]').value = tanggal_log === '-' ? '' : tanggal_log;
                form.querySelector('input[name=jam_kegiatan]').value = jam_kegiatan === '-' ? '' : jam_kegiatan;
                form.querySelector('input[name=aktivitas]').value = aktivitas === '-' ? '' : aktivitas;

                const modalElement = document.querySelector('#modal-edit');
                modalElement.querySelector('.modal-title').textContent = 'Edit Log Aktivitas';
                const modal = new coreui.Modal(modalElement);
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');

                btnTrue.onclick = () => {
                    submitEditForm(form, modal);
                };
                btnFalse.onclick = () => {
                    editCancle(modal);
                };

                modal.show();
            };
        @endif

        const show = (element) => {
            const target = element.parentElement;
            const modalElement = document.querySelector('#modal-show');

            const aktivitas = target.querySelector('div[name=aktivitas]').textContent.trim().replace(/^\s+|\s+$/g,
                    '') ||
                '-';
            const kendala = target.querySelector('div[name=kendala]').textContent || '-';
            const solusi = target.querySelector('div[name=solusi]').textContent || '-';
            const feedback_dosen = target.querySelector('div[name=feedback_dosen]').textContent || '-';
            const tanggal_log = target.querySelector('div[name=tanggal_log]').textContent || '-';
            const jam_kegiatan = target.querySelector('div[name=jam_kegiatan]').textContent || '-';

            modalElement.querySelector('#aktivitas').textContent = aktivitas;
            modalElement.querySelector('#kendala').textContent = kendala;
            modalElement.querySelector('#solusi').textContent = solusi;
            modalElement.querySelector('#feedback_dosen').value = feedback_dosen;
            modalElement.querySelector('#tanggal_log').textContent = new Intl.DateTimeFormat('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).format(new Date(tanggal_log));
            modalElement.querySelector('#jam_kegiatan').textContent = jam_kegiatan;


            modalElement.querySelector('.modal-title').textContent = 'Detail Log Aktivitas';
            const modal = new coreui.Modal(modalElement);
            modal.show();
        };

        const run = () => {
            const contentMid = document.querySelector('#content-mid');
            contentMid.classList.remove('pt-4');
            const contentTop = document.querySelector('#content-top');
            contentTop.classList.remove('flex-fill');
            const button = contentTop.querySelector('.timeline-nav .btn-outline-secondary');
            contentMid.addEventListener('scroll', () => {
                if (contentMid.scrollTop > window.innerHeight * 0.01) {
                    button.style.opacity = 1;
                    button.style.pointerEvents = 'all';
                } else {
                    button.style.opacity = 0;
                    button.style.pointerEvents = 'none';
                }
            });
            fetchData();


        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
