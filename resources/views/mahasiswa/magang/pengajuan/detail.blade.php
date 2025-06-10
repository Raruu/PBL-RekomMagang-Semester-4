@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <style>
        .display-detail {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
        }
    </style>
    @vite(['resources/css/mhs-feedback.css'])
    <div class="d-flex flex-column gap-2 pb-4">
        <div type="button" class="mt-2"
            onclick="{{ $backable ? 'window.history.back()' : 'window.location.href=\'' . route('mahasiswa.magang.pengajuan') . '\'' }}">
            <i class="fas fa-arrow-left"></i> Kembali
        </div>
        <div class="d-flex flex-row w-100 justify-content-between card p-3">
            <div class="d-flex flex-column gap-2">
                <h3 class="fw-bold mb-0">{{ $pengajuanMagang->lowonganMagang->judul_lowongan }} </h3>
                <div class="d-flex flex-column gap-1 mt-1">
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-start">
                        <svg class="icon my-auto ">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-clock') }}"></use>
                        </svg>
                        <p class="mb-0 text-muted">Pengajuan: </p>
                        <div>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($pengajuanMagang->tanggal_pengajuan)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-start">
                        <svg class="icon my-auto ">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-clock') }}"></use>
                        </svg>
                        <p class="mb-0 text-muted"> Mulai: </p>
                        <div>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_mulai)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-row gap-1 align-content-center justify-content-start">
                        <svg class="icon my-auto ">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-flag-alt') }}"></use>
                        </svg>
                        <p class="mb-0 text-muted"> Selesai: </p>
                        <div>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($pengajuanMagang->lowonganMagang->tanggal_selesai)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 align-items-end">
                <div class="d-flex flex-row gap-1 mt-1 justify-content-end">
                    <div style="width: fit-content;">
                        <span
                            class="w-100 py-2 d-flex gap-2 align-items-center badge bg-{{ $pengajuanMagang->status == 'disetujui' ? 'success' : ($pengajuanMagang->status == 'ditolak' ? 'danger' : ($pengajuanMagang->status == 'menunggu' ? 'secondary' : 'info')) }}">
                            {{ Str::ucfirst($statusMagang === 2 && $pengajuanMagang->status != 'selesai' ? 'Finishing' : $pengajuanMagang->status) }}
                        </span>
                    </div>
                    <div style="width: fit-content;">
                        <span
                            class="w-100 py-2 d-flex gap-2 align-items-center badge bg-{{ $statusMagang == 1 ? 'success' : ($statusMagang == 2 ? 'info' : 'secondary') }}">
                            Magang
                            {{ $statusMagang === 0 ? 'Belum Mulai' : ($statusMagang === 1 ? 'Berlangsung' : 'Selesai') }}
                        </span>
                    </div>
                </div>
                @if (in_array($pengajuanMagang->status, ['menunggu', 'ditolak']))
                    <form id="form-batal-pengajuan"
                        action="{{ route('mahasiswa.magang.pengajuan.delete', $pengajuanMagang->pengajuan_id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-danger text-nowrap" id="btn-batal-pengajuan">
                            {{ $pengajuanMagang->status == 'menunggu' ? 'Batalkan Pengajuan' : 'Hapus Pengajuan' }}
                        </button>
                    </form>
                @endif
                @if (in_array($pengajuanMagang->status, ['selesai', 'disetujui']))
                    <div class="d-flex flex-row gap-1 text-start">
                        <a class="btn btn-primary d-flex align-items-center justify-content-start gap-1 text-nowrap"
                            href="{{ route('mahasiswa.magang.log-aktivitas', $pengajuanMagang->pengajuan_id) }}">
                            <svg class="nav-icon me-1" style="width: 20px; height: 20px;">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-notes') }}">
                                </use>
                            </svg>
                            <p class="mb-0">Log Aktivitas</p>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card d-flex flex-column">
            <ul class="nav nav-tabs" id="pengajuan-tabs">
                <li class="nav-item">
                    <a class="nav-link active" style="cursor: pointer; color: var(--foreground)">Lowongan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Dokumen Pengajuan</a>
                </li>
                @if (in_array($pengajuanMagang->status, ['selesai', 'disetujui']))
                    <li class="nav-item">
                        <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Dosen Pembimbing</a>
                    </li>
                @endif
                @if ($statusMagang == 2 || $pengajuanMagang->status == 'selesai')
                    <li class="nav-item">
                        <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Surat Keterangan Magang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="cursor: pointer; color: var(--foreground)">Feedback Magang</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex flex-row w-100" id="display">
                @include('mahasiswa.magang.pengajuan.detail-lowongan')
            </div>
        </div>
        <x-modal-yes-no id="modal-yes-no" dismiss="false" static="true" btnTrue="Ya"
            title="{{ $pengajuanMagang->status == 'menunggu' ? 'Batalkan' : 'Hapus' }}?">
            <x-slot name="btnTrue">
                <x-btn-submit-spinner size="22" wrapWithButton="false">
                    {{ $pengajuanMagang->status == 'menunggu' ? 'Ya, Batalkan' : 'Hapus' }}
                </x-btn-submit-spinner>
            </x-slot>
            Batalkan pengajuan ini?
        </x-modal-yes-no>
    </div>

    @include('mahasiswa.magang.pengajuan.script-feedback')
    @include('mahasiswa.magang.pengajuan.script-dokumen-hasil')
    @include('mahasiswa.magang.pengajuan.script-lowongan')
    <script>
        const lowonganOpenModalPreviewPdf = (link, name) => {
            const modalElement = document.querySelector('#modal-pdf-preview');
            const modal = new coreui.Modal(modalElement);
            modalElement.querySelector('.modal-title').textContent = `Dokumen Pendukung: ${name}`;
            const iframe = modalElement.querySelector('.pdf_preview');
            iframe.src = link;
            modal.show();
        };

        const run = () => {
            const btnBatalPengajuan = document.querySelector('#btn-batal-pengajuan');
            if (btnBatalPengajuan) {
                btnBatalPengajuan.onclick = () => {
                    const modalDeleteElement = document.querySelector('#modal-yes-no');
                    modalDeleteElement.querySelector('#btn-true-yes-no').onclick = () => {
                        btnSpinerFuncs.spinBtnSubmit(modalDeleteElement);
                        const form = document.querySelector('#form-batal-pengajuan');
                        $.ajax({
                            url: form.action,
                            method: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    })
                                    .then(() => {
                                        window.location.href =
                                            '{{ route('mahasiswa.magang.lowongan.detail', ['lowongan_id' => $pengajuanMagang->lowonganMagang->lowongan_id]) }}';
                                    });
                            },
                            error: function(response) {
                                console.error(response.responseJSON);
                                Swal.fire(`Gagal!`, response.responseJSON.message,
                                    'error');
                            },
                            complete: function() {
                                btnSpinerFuncs.resetBtnSubmit(modalDeleteElement);
                            }
                        });
                    };
                    const modal = new coreui.Modal(modalDeleteElement);
                    modalDeleteElement.querySelector('#btn-false-yes-no').onclick = () => {
                        modal.hide();
                    };
                    modal.show();
                };
            }

            const pengajuanTabs = document.querySelector('#pengajuan-tabs');
            const display = document.querySelector('#display');
            const tabs = pengajuanTabs.querySelectorAll('li');

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', (event) => {
                    const activeTab = event.target;
                    tabs.forEach(tab => tab.querySelector('a').classList.remove('active'));
                    activeTab.classList.add('active');
                    display.innerHTML = ``;
                    if (index === 0) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-lowongan')`);
                        initLowongan();
                    } else if (index === 1) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-dokumen')`);
                    } else if (index === 2) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-dosen')`);
                    } else if (index === 3) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-dokumen-hasil')`);
                        initDokumenHasil();
                    } else if (index === 4) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-feedback')`);
                        initFeedback();
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
            @if ($open !== null && is_numeric($open))
                const openAt = () => {
                    const open = {{ $open }};
                    if (open >= tabs.length) return;
                    tabs[open].querySelector('a').click();
                };
                openAt();
            @endif
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
