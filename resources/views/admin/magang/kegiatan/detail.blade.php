@extends('layouts.app')
@section('title', 'Detail Kegiatan Magang Mahasiswa')
@section('content-top')
    <style>
        .display-detail {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
        }
    </style>
    <div class="flex-fill d-flex flex-column gap-4 pb-5 mb-4">
        <div class="d-flex flex-row w-100 gap-4 px-5 py-3">
            <div>
                <div class="d-flex flex-column gap-3  sticky-top">
                    <div type="button" class="mt-2" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </div>
                    <div class="d-flex flex-column text-start align-items-start card gap-2 p-3"
                        style="{{ $pengajuanMagang->status == 'selesai' ? 'opacity: 0.5; pointer-events: none' : '' }}">
                        <div class="d-flex flex-row justify-content-between w-100">
                            <h5 class="fw-bold mb-3">Plot Dosen (Penugasan)</h5>
                            <div>
                                <span
                                    class="badge bg-{{ $pengajuanMagang->status == 'disetujui' ? 'success' : ($pengajuanMagang->status == 'ditolak' ? 'danger' : ($pengajuanMagang->status == 'menunggu' ? 'secondary' : 'info')) }}">
                                    <i
                                        class="fas {{ $pengajuanMagang->status == 'disetujui' ? 'fa-check-circle' : ($pengajuanMagang->status == 'ditolak' ? 'fa-times-circle' : ($pengajuanMagang->status == 'menunggu' ? 'fa-clock' : 'fa-check-circle')) }}">
                                    </i>
                                    {{ Str::ucfirst($pengajuanMagang->status) }}
                                </span>
                            </div>
                        </div>
                        <form
                            action="{{ $pengajuanMagang->status == 'selesai' ? '#' : route('admin.magang.kegiatan.update') }}"
                            method="POST" class="flex-fill d-flex flex-column w-100">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="pengajuan_id" name="pengajuan_id" value="{{ $pengajuan_id }}">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select" id="status" required>
                                    <option value="" disabled
                                        {{ $pengajuanMagang->status == 'menunggu' ? 'selected' : '' }}>
                                        Pilih Status</option>
                                    @foreach ($statuses as $status)
                                        @if ($status == 'menunggu' || $status == 'selesai')
                                            @continue
                                        @endif
                                        <option value="{{ $pengajuanMagang->status == $status ? '' : $status }}"
                                            {{ $pengajuanMagang->status == $status ? 'selected' : '' }}>
                                            {{ Str::ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <div id="error-status" class="text-danger"></div>

                            </div>
                            <div class="mb-3">
                                <label for="dosen_id" class="form-label">Dosen</label>
                                <select name="dosen_id" class="form-select" id="dosen_id" required>
                                    <option value="" {{ $pengajuanMagang->dosen_id == null ? 'selected' : '' }}
                                        disabled>
                                        Pilih Dosen</option>
                                    @foreach ($dosen->sortBy('nama') as $item)
                                        <option value="{{ $item->dosen_id }}"
                                            {{ ($pengajuanMagang->dosen_id ?? '') == $item->dosen_id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="error-dosen_id" class="text-danger"></div>
                            </div>
                            <button type="button" id="btn-submit" class="btn btn-primary">
                                <x-btn-submit-spinner wrapWithButton="false" size="22">
                                    <i class="fas fa-save"></i> Simpan
                                </x-btn-submit-spinner>
                            </button>
                        </form>
                    </div>

                    <div class="d-flex flex-column text-start card p-3" style="height: fit-content;">
                        <ul class="sidebar-nav pt-0" id="info-tabs">
                            <li class="nav-title p-0">Lihat</li>
                            <li class="nav-item" style="cursor: pointer;">
                                <a class="nav-link active" id="collapsePribadi">
                                    <svg class="nav-icon">
                                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contact') }}">
                                        </use>
                                    </svg> Informasi Dosen
                                </a>
                            </li>
                            <li class="nav-item" style="cursor: pointer;">
                                <a class="nav-link" id="collapsePreferensi">
                                    <svg class="nav-icon">
                                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contact') }}">
                                        </use>
                                    </svg> Informasi Mahasiswa
                                </a>
                            </li>
                            <li class="nav-item" style="cursor: pointer;">
                                <a class="nav-link" id="collapseSkill">
                                    <svg class="nav-icon">
                                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-briefcase') }}">
                                        </use>
                                    </svg> Lowongan & Dokumen
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="vr mt-5"></div>

            <div class="d-flex flex-row w-100 card p-3 mt-5" id="display" style="height: fit-content;">
                @include('admin.magang.kegiatan.detail-dosen')
            </div>
        </div>
    </div>

    <x-modal-yes-no title="Konfirmasi" id="modal-submit-confirm" dismiss="false" static="true">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                <i class="fas fa-save"></i> Simpan
            </x-btn-submit-spinner>
        </x-slot>
        <x-slot name="btnFalse">
            <i class="fas fa-times"></i> Batal
        </x-slot>
        <div class="d-flex flex-column gap-1">
            <div class="d-flex flex-column gap-0">
                <h6 class="fw-bold">Status</h6>
                <div>
                    <span class="modal_status badge">
                        <i class="fas"></i> <span class="modal_status_text"></span>
                    </span>
                </div>
            </div>
            <div class="d-flex flex-column gap-0">
                <h6 class="fw-bold">Dosen</h6>
                <p class="modal_dosen"></p>
            </div>
            <div class="d-flex flex-column gap-0">
                <h6 class="fw-bold">Catatan <span class="catatan_required text-danger d-none">*</span><span
                        class="catatan_optional text-muted d-none">* Opsional</span></h6>
                <textarea class="form-control" id="modal_catatan" name="catatan" rows="4"></textarea>
                <div id="error-catatan" class="text-danger"></div>
            </div>
        </div>
    </x-modal-yes-no>

    @include('admin.magang.kegiatan.script-mahasiswa')
    @include('admin.magang.kegiatan.script-dosen')
    @include('admin.magang.kegiatan.script-lowongan')
    <script>
        const run = () => {
            const infoTabs = document.querySelector('#info-tabs');
            const display = document.querySelector('#display');
            const tabs = infoTabs.querySelectorAll('li');
            const dosenSelector = document.querySelector('#dosen_id');

            tabs.forEach((tab, _index) => {
                const index = _index - 1;
                tab.addEventListener('click', (event) => {
                    const activeTab = event.target;
                    tabs.forEach(tab => {
                        const a = tab.querySelector('a');
                        if (a) a.classList.remove('active')
                    });
                    activeTab.classList.add('active');
                    display.innerHTML = ``;
                    if (index === 0) {
                        display.insertAdjacentHTML('afterbegin', `@include('admin.magang.kegiatan.detail-dosen')`);
                        fetchDosen(dosenSelector.value);
                    } else if (index === 1) {
                        display.insertAdjacentHTML('afterbegin', `@include('admin.magang.kegiatan.detail-mahasiswa')`);
                        kecocokanSkill();
                    } else if (index === 2) {
                        display.insertAdjacentHTML('afterbegin', `@include('admin.magang.kegiatan.detail-lowongan')`);
                        initLowongan();
                    }
                    setTimeout(() => {
                        const displayDetail = display.querySelector('.display-detail');
                        if (displayDetail) {
                            displayDetail.style.opacity = '';
                        }
                    }, 0);
                });
            });
            display.querySelector('.display-detail').style.opacity = '';
            fetchDosen(dosenSelector.value);
            dosenSelector.addEventListener('change', (event) => {
                const a = tabs[1].querySelector('a');
                if (a.classList.contains('active')) {
                    fetchDosen(event.target.value);
                } else {
                    tabs[1].querySelector('a').click();
                }
            });


            const form = document.querySelector('form');
            const statusSelector = form.querySelector('#status');
            const btnSubmit = document.querySelector('#btn-submit');

            const checkForBtnSubmit = () => {
                const dosenValue = dosenSelector.value.trim();
                const statusValue = statusSelector.value.trim();
                btnSubmit.disabled = !(dosenValue && statusValue);
            };

            dosenSelector.addEventListener('change', checkForBtnSubmit);
            statusSelector.addEventListener('change', checkForBtnSubmit);
            checkForBtnSubmit();

            btnSubmit.onclick = () => {
                const modalElement = document.querySelector('#modal-submit-confirm');
                const modal = new coreui.Modal(modalElement);
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');

                const formData = new FormData(form);

                const status = formData.get('status');
                const dosen = formData.get('dosen_id');
                const statusBadge = modalElement.querySelector('.modal_status');
                const badgeIcon = statusBadge.querySelector('i');
                statusBadge.classList.remove('bg-success', 'bg-danger');
                const catatanRequired = modalElement.querySelector('.catatan_required');
                catatanRequired.classList.add('d-none');
                const catatanOptional = modalElement.querySelector('.catatan_optional');
                catatanOptional.classList.add('d-none');
                badgeIcon.classList.remove('fa-check-circle', 'fa-times-circle');

                if (status === 'disetujui') {
                    statusBadge.classList.add('bg-success');
                    badgeIcon.classList.add('fa-check-circle');
                    catatanOptional.classList.remove('d-none');
                } else if (status === 'ditolak') {
                    statusBadge.classList.add('bg-danger');
                    badgeIcon.classList.add('fa-times-circle');
                    catatanRequired.classList.remove('d-none');
                }

                modalElement.querySelector('.modal_status_text').textContent = status.charAt(0).toUpperCase() + status.slice(1);
                const dosenOption = dosenSelector.querySelector(`option[value="${dosen}"]`);
                modalElement.querySelector('.modal_dosen').textContent = dosenOption?.textContent || '';

                btnFalse.onclick = () => {
                    modal.hide();
                };

                btnTrue.onclick = () => {
                    if (status === 'ditolak') {
                        const modalCatatan = modalElement.querySelector('#modal_catatan').value.trim();
                        if (status === 'ditolak' && !modalCatatan) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Catatan tidak boleh kosong, jika penolakan',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                    }
                    btnSpinerFuncs.spinBtnSubmit(modalElement);
                    formData.append('catatan_admin', modalElement.querySelector('#modal_catatan').value);
                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
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
                            console.error('Error:', response.responseJSON);
                            Swal.fire({
                                title: "Gagal!",
                                text: response.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            $.each(response.responseJSON.msgField,
                                function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                            btnSpinerFuncs.resetBtnSubmit(modalElement);
                        },
                        complete: function() {
                            btnSpinerFuncs.resetBtnSubmit(modalElement);
                        }
                    });
                };

                modal.show();


            };
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
