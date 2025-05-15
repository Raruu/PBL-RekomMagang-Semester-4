@extends('layouts.app')
@section('title', 'Detail Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="d-flex p-1 flex-row w-100 justify-content-between">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            @if (in_array($pengajuanMagang->status, ['selesai', 'disetujui']))
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#logAktivitasModal">
                    <svg class="nav-icon" style="width: 20px; height: 20px;">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-featured-playlist') }}">
                        </use>
                    </svg> Log Aktivitas
                </button>
            @endif
        </div>
        <div class="card flex-row w-100">
            <div class="card-body d-flex flex-column gap-2 flex-fill">
                <div class="d-flex flex-row gap-2 align-items-end justify-content-between">
                    <div class="d-flex flex-row gap-2 align-items-end">
                        <h3 class="fw-bold mb-0">{{ $pengajuanMagang->lowonganMagang->judul_lowongan }} </h3>
                    </div>
                </div>
                <div class="d-flex flex-row gap-2">
                    <span class="badge my-auto bg-{{ $pengajuanMagang->lowonganMagang->is_active ? 'success' : 'danger' }}">
                        {{ $pengajuanMagang->lowonganMagang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                    <p class="mb-0 text-muted">
                        Batas Pendaftaran: {{ $pengajuanMagang->lowonganMagang->batas_pendaftaran }} atau
                        <strong>
                            {{ date_diff(date_create($pengajuanMagang->lowonganMagang->batas_pendaftaran), date_create(date('Y-m-d')))->format('%a') }}</strong>
                        hari lagi
                    </p>
                </div>
                <div class="d-flex flex-column gap-2 mt-1">
                    <h5 class="fw-bold mb-0"><span class="text-muted">Posisi:</span>
                        {{ $pengajuanMagang->lowonganMagang->judul_posisi }} </h5>
                    <p>
                        {{ $pengajuanMagang->lowonganMagang->deskripsi }}
                    </p>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Persyaratan Magang</h5>
                    <ul class="list-unstyled">
                        @foreach (explode("\n", $pengajuanMagang->lowonganMagang->persyaratanMagang->deskripsi_persyaratan) as $deskripsiPersyaratan)
                            <li>&#8226; {{ $deskripsiPersyaratan }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Skill Minimum</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                            @php
                                $keahlianLowongan = $pengajuanMagang->lowonganMagang->keahlianLowongan->where(
                                    'kemampuan_minimum',
                                    $keytingkatKemampuan,
                                );
                            @endphp
                            @if (!$keahlianLowongan->isEmpty())
                                <div class="d-flex flex-column">
                                    <p class="fw-bold mb-0"> &#8226; <span>{{ $tingkatKemampuan }}</span> </p>
                                    <div class="d-flex flex-row gap-1 flex-wrap ps-2 _keahlian">
                                        @foreach ($keahlianLowongan as $keahlianMahasiswa)
                                            <span
                                                class="badge badge-sm 
                                            @if ($keytingkatKemampuan == 'ahli') bg-danger 
                                            @elseif ($keytingkatKemampuan == 'mahir') bg-warning 
                                            @elseif ($keytingkatKemampuan == 'menengah') bg-primary 
                                            @else bg-info @endif">{{ $keahlianMahasiswa->keahlian->nama_keahlian }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="d-flex flex-column gap-1 mt-1">
                    <h5 class="fw-bold mb-0">Tentang Lowongan</h5>
                    <div class="d-flex flex-row gap-2">
                        <div class="d-flex flex-row gap-1 align-content-center justify-content-center">
                            <svg class="icon my-auto ">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}"></use>
                            </svg>
                            <span class="badge bg-primary my-auto">
                                {{ ucfirst($pengajuanMagang->lowonganMagang->tipe_kerja_lowongan) }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card m-4" style="height: fit-content; max-width: 250px;">
                <div class="card-body d-flex flex-column flex-fill text-center">
                    <div class="flex-fill w-100 h-100 d-flex flex-column">
                        <span
                            class="w-100 h-100 fs-5 py-2 badge bg-{{ $pengajuanMagang->status == 'disetujui' ? 'success' : ($pengajuanMagang->status == 'ditolak' ? 'danger' : ($pengajuanMagang->status == 'menunggu' ? 'secondary' : 'info')) }}">
                            {{ Str::ucfirst($pengajuanMagang->status) }}
                        </span>
                        @if ($pengajuanMagang->status == 'menunggu')
                            <hr class="my-2">
                            <form id="form-batal-pengajuan flex-fill w-100"
                                action="{{ route('mahasiswa.magang.pengajuan.delete', $pengajuanMagang->pengajuan_id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger w-100" id="btn-batal-pengajuan">Batalkan
                                    Pengajuan</button>
                            </form>
                        @endif
                    </div>
                    <hr class="my-2">
                    <h4 class="mb-0">
                        <span
                            class="badge bg-info mb-0  {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'bg-info' : 'bg-danger' }}">
                            {{ $pengajuanMagang->lowonganMagang->gaji > 0 ? 'Rp. ' . $pengajuanMagang->lowonganMagang->gaji : 'Tidak ada gaji' }}
                        </span>
                    </h4>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1 text-start">
                        <h6 class="fw-bold mb-0">Informasi Perusahaan</h6>
                        <p class="mb-0 small">
                            {{ $pengajuanMagang->lowonganMagang->perusahaan->nama_perusahaan }}
                        </p>
                        <p class="mb-0 small"><span class="text-muted">Bidang Industri:</span>
                            {{ $pengajuanMagang->lowonganMagang->perusahaan->bidang_industri }}
                        </p>

                        <a class="mb-0 small" target="_blank"
                            href="{{ $pengajuanMagang->lowonganMagang->perusahaan->website }}">
                            {{ $pengajuanMagang->lowonganMagang->perusahaan->website }}
                        </a>
                        <a class="mb-0 small"
                            href="mailto:{{ $pengajuanMagang->lowonganMagang->perusahaan->kontak_email }}">
                            {{ $pengajuanMagang->lowonganMagang->perusahaan->kontak_email }}
                        </a>
                        <p class="mb-0 small"><span class="text-muted">Telepon:</span>
                            {{ $pengajuanMagang->lowonganMagang->perusahaan->kontak_telepon }}
                        </p>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex flex-column gap-1 text-start">
                        <h6 class="fw-bold mb-0">Lokasi</h6>
                        <a href="https://maps.google.com/?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                            target="_blank">
                            {{ $lokasi->alamat }}
                        </a>
                        <p class="mb-0 small"><span class="text-muted">Jarak dengan preferensi:<br /></span>
                            {{ number_format($jarak, 2) }} <span class="text-muted fw-bold">KM</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <x-modal-yes-no id="modal-yes-no" dismiss=false btnTrue="<span id='btn-submit-text'>Ya</span>">
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
                        fetch(form.action, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    Swal.fire({
                                            title: 'Berhasil!',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        })
                                        .then(() => {
                                            window.location.href =
                                                '{{ url('/mahasiswa/magang/pengajuan') }}';
                                        });
                                } else {
                                    console.log(data);
                                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error(error);
                                Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                            });
                    };
                    const modal = new coreui.Modal(modalDeleteElement);
                    modal.show();
                };
            }


        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
