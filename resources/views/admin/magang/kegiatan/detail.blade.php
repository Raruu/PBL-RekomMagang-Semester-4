@extends('layouts.app')
@section('title', 'Detail Kegiatan Magang Mahasiswa')
@section('content')
    <style>
        .display-detail {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
        }
    </style>
    <div class="flex-fill d-flex flex-column flex-wrap h-100 gap-4 pb-5">
        <div class="d-flex flex-column text-start align-items-start w-100">
            <h4 class="fw-bold mb-3">Plot Dosen (Penugasan)</h4>
            <div class="d-flex flex-row text-start align-items-start card gap-4 p-3 w-100">
                <form action="{{ route('admin.magang.kegiatan.post') }}" method="POST" class="flex-fill d-flex flex-row">
                    @csrf
                    <input type="hidden" id="pengajuan_id" name="pengajuan_id" value="{{ $pengajuan_id }}">
                    <div class="flex-fill">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="status" required>
                                <option value="" disabled
                                    {{ $pengajuanMagang->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                @foreach ($statuses as $status)
                                    @if ($status != 'menunggu')
                                        <option value="{{ $status }}"
                                            {{ $pengajuanMagang->status == $status ? 'selected' : '' }}>
                                            {{ Str::ucfirst($status) }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="error-status" class="text-danger"></div>
                        </div>
                        <div class="mb-3">
                            <label for="dosen_id" class="form-label">Dosen</label>
                            <select name="dosen_id" class="form-select" id="dosen_id" required>
                                <option value="" {{ $pengajuanMagang->dosen_id == null ? 'selected' : '' }} disabled>
                                    Pilih Dosen</option>
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->dosen_id }}"
                                        {{ ($pengajuanMagang->dosen_id ?? '') == $item->dosen_id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="error-dosen_id" class="text-danger"></div>
                        </div>
                        <div class="d-flex flex-row justify-content-start gap-2 p-2">
                            <button type="button" id="btn-submit" class="btn btn-primary">
                                <x-btn-submit-spinner wrapWithButton="false" size="22">
                                    <i class="fas fa-save"></i> Simpan
                                </x-btn-submit-spinner>
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card d-flex flex-column">
            <ul class="nav nav-tabs" id="info-tabs">
                <li class="nav-item">
                    <a class="nav-link active" style="cursor: pointer; color: var(--foreground)">Dosen Pembimbing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Info Lowongan</a>
                </li>
            </ul>

            <div class="d-flex flex-row w-100" id="display">
                @include('admin.magang.kegiatan.detail-dosen')
            </div>
        </div>
    </div>

    @include('admin.magang.kegiatan.script-mahasiswa')
    @include('admin.magang.kegiatan.script-dosen')
    <script>
        const run = () => {
            const infoTabs = document.querySelector('#info-tabs');
            const display = document.querySelector('#display');
            const tabs = infoTabs.querySelectorAll('li');
            const dosenSelector = document.querySelector('#dosen_id');

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', (event) => {
                    const activeTab = event.target;
                    tabs.forEach(tab => tab.querySelector('a').classList.remove('active'));
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
                fetchDosen(event.target.value);
            });


            const form = document.querySelector('form');
            const btnSubmit = document.querySelector('#btn-submit');
            btnSubmit.onclick = () => {
                btnSpinerFuncs.spinBtnSubmit(form);
                $.ajax({
                    url: form.action,
                    type: 'POST',
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
                            window.location.reload();
                        });
                    },
                    error: function(response) {
                        console.error('Error:', response.responseJSON);
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
                    },
                    complete: function() {
                        btnSpinerFuncs.resetBtnSubmit(form);
                    }
                });
            };
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
