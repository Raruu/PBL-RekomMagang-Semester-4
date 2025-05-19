@extends('layouts.app')
@section('title', 'Log Aktivitas Mahasiswa')

@push('styles')
    @vite(['resources/css/timeline.css'])
@endpush

@section('content')
    <div class="d-flex flex-column text-start gap-3 pb-3">
        <div class="d-flex p-1 flex-row w-100 justify-content-between">
            <h4 class="fw-bold mb-0">Log Aktivitas</h4>
            <div class="d-flex flex-row gap-2">
                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i> Reload
                </button>
                <button type="button" class="btn btn-primary" onclick="add()">
                    <i class="fas fa-plus"></i> Tambah Log
                </button>
            </div>
        </div>
    </div>
    <div class="position-relative">
        <div class="timeline position-relative">
            <div class="timeline-line"></div>
            @foreach ($logAktivitas as $key => $logs)
                <div class="timeline-item">
                    <div class="timeline-marker info"></div>
                    <div class="fw-bold" id="{{ $key }}">{{ $key }}</div>
                </div>
                @foreach ($logs as $log)
                    @php
                        $hasAdditionalInfo =
                            !empty($log->feedback_dosen) || !empty($log->solusi) || !empty($log->kendala);
                    @endphp
                    <div class="timeline-item ms-4 ">
                        <div class="timeline-line-horizontal"></div>
                        <div class="timeline-marker sub-marker secondary"></div>
                        <div class="d-none">
                            <div name="kendala">{{ $log->kendala }}</div>
                            <div name="solusi">{{ $log->solusi }}</div>
                            <div name="feedback_dosen">{{ $log->feedback_dosen }}</div>
                            <div name="log_id">{{ $log->log_id }}</div>
                            <div name="tanggal_log">{{ $key }}</div>
                            <div name="jam_kegiatan">{{ $log->jam_kegiatan }}</div>
                        </div>
                        <div class="timeline-date">Jam: {{ \Carbon\Carbon::parse($log->jam_kegiatan)->format('H:i') }}</div>
                        <div class="timeline-content bg-light hover d-flex flex-row justify-content-between {{ $hasAdditionalInfo ? 'primary-line ' : 'secondary-line' }}"
                            onclick="show(this)">
                            <div name="aktivitas" class="d-flex align-items-center justify-content-center">
                                {{ $log->aktivitas }}</div>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="event.stopPropagation(); edit(this)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        <div class="text-center d-flex flex-column align-items-center pb-5">
            <img src="{{ asset('imgs/sanhua-froze.webp') }}" alt="ice" style="width: 16rem">
            <h2><i><b>Itu saja</b></i></h2>
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
            <input type="time" name="jam_kegiatan" id="jam_kegiatan" class="form-control" step="1" required>
            <div id="error-jam_kegiatan" class="text-danger"></div>
        </form>
    </x-modal-yes-no>

    <script>
        const resetForm = () => {
            const modalElement = document.querySelector('#modal-edit');
            const form = document.querySelector('#modal-edit form');
            form.reset();
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
            if (!isValid)
                return;

            const data = new FormData(form);
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
                        const url = new URL(window.location.href);
                        const oldHref = url.href;
                        window.location.href = oldHref.replace(/#.*$/, '') +
                            `#${data.get('tanggal_log')}`;
                        window.location.reload();
                    });
                },
                error: function(response) {
                    console.log(response.responseJSON);
                    Swal.fire({
                        title: `Gagal ${response.status}`,
                        text: response.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
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
            const aktivitas = target.querySelector('div[name=aktivitas]').textContent.trim().replace(/^\s+|\s+$/g, '');
            const form = document.querySelector('#modal-edit form');
            form.querySelector('input[name=log_id]').value = log_id;
            form.querySelector('input[name=kendala]').value = kendala;
            form.querySelector('input[name=solusi]').value = solusi;
            form.querySelector('input[name=tanggal_log]').value = tanggal_log;
            form.querySelector('input[name=jam_kegiatan]').value = jam_kegiatan;
            form.querySelector('input[name=aktivitas]').value = aktivitas;

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
            modalElement.querySelector('#tanggal_log').textContent = tanggal_log;
            modalElement.querySelector('#jam_kegiatan').textContent = jam_kegiatan;


            modalElement.querySelector('.modal-title').textContent = 'Detail Log Aktivitas';
            const modal = new coreui.Modal(modalElement);
            modal.show();
        };

        const run = () => {};
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
