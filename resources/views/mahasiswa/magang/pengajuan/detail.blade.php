@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="d-flex p-1 flex-row w-100 justify-content-between">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <div class="d-flex flex-row gap-2">
                @if ($pengajuanMagang->status == 'menunggu')
                    <hr class="my-2">
                    <form id="form-batal-pengajuan" class="flex-fill w-100"
                        action="{{ route('mahasiswa.magang.pengajuan.delete', $pengajuanMagang->pengajuan_id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger w-100 text-nowrap" id="btn-batal-pengajuan">
                            Batalkan Pengajuan
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
                        @if ($pengajuanMagang->status == 'selesai')
                            <a class="btn btn-primary d-flex align-items-center justify-content-start gap-1 text-nowrap">
                                <svg class="nav-icon me-1" style="width: 20px; height: 20px;">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-spreadsheet') }}">
                                    </use>
                                </svg>
                                <p class="mb-0">Surat Keterangan</p>
                            </a>
                            <a class="btn btn-primary d-flex align-items-center justify-content-start gap-1 text-nowrap">
                                <svg class="nav-icon me-1" style="width: 20px; height: 20px;">
                                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-smile') }}">
                                    </use>
                                </svg>
                                <p class="mb-0">Feedback Evaluasi</p>
                            </a>
                        @endif
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
            </ul>

            <div class="d-flex flex-row w-100" id="display">
                @include('mahasiswa.magang.pengajuan.detail-lowongan')
            </div>
        </div>
        <x-modal-yes-no id="modal-yes-no" dismiss="true" btnTrue="Ya">
            Batalkan pengajuan ini?
        </x-modal-yes-no>
    </div>
    <script>
        const run = () => {
            const btnBatalPengajuan = document.querySelector('#btn-batal-pengajuan');
            if (btnBatalPengajuan) {
                btnBatalPengajuan.onclick = () => {
                    const modalDeleteElement = document.querySelector('#modal-yes-no');
                    modalDeleteElement.querySelector('#btn-true-yes-no').onclick = () => {
                        const form = document.querySelector('#form-batal-pengajuan');
                        $.ajax({
                            url: form.action,
                            method: 'DELETE',
                            success: function(response) {
                                if (response.status) {
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
                                }
                            },
                            error: function(response) {
                                console.error(response.responseJSON);
                                Swal.fire(`Gagal ${response.status}`, response.responseJSON.message,
                                    'error');
                            }
                        });
                    };
                    const modal = new coreui.Modal(modalDeleteElement);
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
                    } else if (index === 1) {
                        display.insertAdjacentHTML('afterbegin', `@include('mahasiswa.magang.pengajuan.detail-dokumen')`);
                    }
                });
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
