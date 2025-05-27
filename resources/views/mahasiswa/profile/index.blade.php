@extends('layouts.app')
@section('content')
    <x-picture-display-full />
    <div class="d-flex flex-row gap-4 pb-4 position-relative">
        <div style="width: 334px; min-width: 334px"></div>
        <div class="d-flex flex-column text-start gap-3 position-fixed pb-5 z-1"
            style="top: 138px; max-height: calc(100vh - 118px); overflow-y: auto; width: 334px; min-width: 334px; max-width: 334px;">
            <h4 class="fw-bold mb-0">Profil Mahasiswa</h4>
            <div class="d-flex flex-column text-start align-items-center card p-3 position-relative"
                style="height: fit-content; max-width: 334px;">
                <div class="d-flex flex-row gap-3" style="min-width: 300px; max-width: 300px;">
                    <div for="profile_picture" class="position-relative"
                        style="min-width: 90px; width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
                        <img src="{{ Auth::user()->getPhotoProfile() ? asset($user->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                            alt="Profile Picture" class="w-100" id="picture-display">
                        <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                            style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                            onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 0;"
                            onclick="document.getElementById('full-screen-image').style.display = 'flex';
                    document.getElementById('picture-display-full').src = document.querySelector('#picture-display').src;">
                            <svg class="position-absolute text-white h-auto"
                                style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 15%">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}">
                                </use>
                            </svg>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="fw-bold mb-0 text-wrap" style="font-weight: 500;">{{ $user->nama }}</p>
                        <p class="mb-0 text-muted">{{ $user->nim }}</p>
                        <p class="fw-bold mb-0">{{ $user->programStudi->nama_program }}</p>
                        <p class="fw-bold mb-0"> <span class="text-muted">Angkatan: </span>{{ $user->angkatan }}</p>
                        <p class="fw-bold mb-0"> <span class="text-muted">IPK Komulatif: </span>{{ $user->ipk }}</p>
                    </div>
                </div>
                <a href="{{ route('mahasiswa.profile.edit') }}" class="btn btn-primary mt-3 w-100">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </a>
                <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
                <div class="d-flex flex-column w-100">
                    <h5 class="fw-bold mb-2">Keahlian</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                            <div class="d-flex flex-column">
                                <p class="fw-bold mb-0"> &#8226; <span>{{ $tingkatKemampuan }}</span> </p>
                                <div class="d-flex flex-row gap-1 flex-wrap ps-2 _keahlian">
                                    @foreach ($keahlian_mahasiswa as $keahlianMahasiswa)
                                        @if ($keahlianMahasiswa->tingkat_kemampuan == $keytingkatKemampuan)
                                            <span
                                                class="badge badge-sm 
                                            @if ($keytingkatKemampuan == 'ahli') bg-danger 
                                            @elseif ($keytingkatKemampuan == 'mahir') bg-warning 
                                            @elseif ($keytingkatKemampuan == 'menengah') bg-primary 
                                            @else bg-info @endif">{{ $keahlianMahasiswa->keahlian->nama_keahlian }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="position-relative w-100">
            <div class="d-flex flex-column gap-3 flex-fill" id="profile-content"
                style="transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1); opacity: 1;">
                <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex flex-row gap-3 flex-fill">
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <h5 class="card-title">Email</h5>
                                    <p class="card-text">{{ $user->user->email }}</p>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <h5 class="card-title">Nomor Telepon</h5>
                                    <p class="card-text">{{ $user->nomor_telepon }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold mb-0">Preferensi Magang</h4>
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex flex-row gap-3 flex-fill">
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <h5 class="card-title">Posisi</h5>
                                    <p class="card-text">{{ $user->preferensiMahasiswa->posisi_preferensi }}</p>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <h5 class="card-title">Tipe Kerja</h5>
                                    <p class="card-text">
                                        {{ $tipe_kerja_preferensi[$user->preferensiMahasiswa->tipe_kerja_preferensi] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h5 class="card-title">Lokasi</h5>
                            <p class="card-text">{{ $user->preferensiMahasiswa->lokasi->alamat }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold mb-0">Pengalaman</h4>
                <div class="card w-100">
                    <div class="card-header">
                        <h6 class="fw-bold pb-0 mb-0">Kerja</h6>
                    </div>
                    <div class="card-body p-0 w-100">
                        @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'kerja') as $key => $pengalaman)
                            <div class="d-flex flex-column gap-1 flex-fill background-hoverable p-3">
                                <div class="d-flex flex-column gap-1 flex-fill" style="cursor: pointer;"
                                    onClick="openKeahlian(this)">
                                    <h7 class="fw-bold mb-0" id="display-nama_pengalaman">
                                        {{ $pengalaman->nama_pengalaman }}
                                    </h7>
                                    <p class="mb-0" id="display-deskripsi_pengalaman">
                                        {{ $pengalaman->deskripsi_pengalaman }}
                                    </p>
                                    <input type="hidden" name="tipe_pengalaman[]"
                                        value="{{ $pengalaman->tipe_pengalaman }}">
                                    <input type="hidden" name="periode_mulai[]" value="{{ $pengalaman->periode_mulai }}">
                                    <input type="hidden" name="periode_selesai[]"
                                        value="{{ $pengalaman->periode_selesai }}">
                                    <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
                                    <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                                        @foreach ($pengalaman->pengalamanTag as $tag)
                                            <span
                                                class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-2">
                            @endif
                        @empty
                            <p class="mb-0">Tidak ada</p>
                        @endforelse
                    </div>
                    <div class="card-header">
                        <h6 class="fw-bold pb-0 mb-0">Lomba</h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'lomba') as $key => $pengalaman)
                            <div class="d-flex flex-column gap-1 flex-fill background-hoverable p-3">
                                <div class="d-flex flex-column gap-1 flex-fill" style="cursor: pointer;"
                                    onClick="openKeahlian(this)">
                                    <h7 class="fw-bold mb-0" id="display-nama_pengalaman">
                                        {{ $pengalaman->nama_pengalaman }}
                                    </h7>
                                    <p class="mb-0" id="display-deskripsi_pengalaman">
                                        {{ $pengalaman->deskripsi_pengalaman }}</p>
                                    <input type="hidden" name="tipe_pengalaman[]"
                                        value="{{ $pengalaman->tipe_pengalaman }}">
                                    <input type="hidden" name="periode_mulai[]"
                                        value="{{ $pengalaman->periode_mulai }}">
                                    <input type="hidden" name="periode_selesai[]"
                                        value="{{ $pengalaman->periode_selesai }}">
                                    <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
                                    <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                                        @foreach ($pengalaman->pengalamanTag as $tag)
                                            <span
                                                class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-2">
                            @endif
                        @empty
                            <p class="mb-0">Tidak ada</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="position-absolute top-0 left-0 w-100 h-100" style="pointer-events: none;">
                <div class="collapse collapse-horizontal" id="keahlian_collapse">
                    <div class="d-flex flex-row align-items-center" style="cursor: pointer;" onclick="closeKeahlian()">
                        <div href="" class="me-2">
                            <svg class="icon icon-1xl" style="margin-top: 2px;">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-arrow-left') }}">
                                </use>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-0">Pengalaman</h4>
                    </div>
                    <div class="card card-body shadow-sm mt-3">
                        <div class="d-flex flex-column gap-1">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const closeKeahlian = () => {
            const keahlianCollapse = document.querySelector('#keahlian_collapse');
            keahlianCollapse.style.pointerEvents = 'none';
            setTimeout(() => {
                keahlianCollapse.querySelector('.d-flex.flex-column.gap-1').innerHTML = '';
            }, 300);
            const profileContent = document.querySelector('#profile-content');
            profileContent.style.pointerEvents = 'auto';
            profileContent.style.opacity = 1;
            profileContent.style.maxHeight = '';
            profileContent.style.overflow = '';

            const collapse = new coreui.Collapse(keahlianCollapse, {
                toggle: false
            })
            collapse.hide();
        }

        const openKeahlian = (target) => {
            const keahlianCollapse = document.querySelector('#keahlian_collapse');
            keahlianCollapse.style.pointerEvents = 'auto';
            keahlianCollapse.querySelector('.card.card-body').style.width =
                `${target.parentElement.parentElement.clientWidth}px`;

            keahlianCollapse.querySelector('.d-flex.flex-column.gap-1').innerHTML = target.innerHTML;
            if (target.querySelector('input[name="tipe_pengalaman[]"][value="lomba"]')) {
                const pdfPreview = document.createElement('div');
                pdfPreview.classList.add('mt-3');
                const pathFile = target.querySelector('#path_file').textContent;
                if (pathFile) {
                    pdfPreview.classList.add('ratio', 'ratio-1x1');
                    pdfPreview.innerHTML = `
                    <object data="${pathFile}" type="application/pdf">
                        <p>Linknya ada, filenya engga tau: <a href="${pathFile}">PDF</a>.</p>
                    </object>
                `;
                } else {
                    pdfPreview.innerHTML = `
                    <p class="mb-0 text-muted fw-bold fs-5">Tidak ada file</p>
                `;
                }

                keahlianCollapse.querySelector('.d-flex.flex-column.gap-1').append(pdfPreview);
            } else {
                const periodeElement = document.createElement('div');
                periodeElement.classList.add('d-flex', 'flex-row', 'gap-2', 'mt-3');
                periodeElement.innerHTML = `
                    <div>
                        <p class="mb-0">Periode Mulai</p>
                        <p class="mb-0">${target.querySelector('input[name="periode_mulai[]"]').value}</p>
                    </div>
                    <div>
                        <p class="mb-0">Periode Akhir</p>
                        <p class="mb-0">${target.querySelector('input[name="periode_selesai[]"]').value}</p>
                    </div>
                `;
                keahlianCollapse.querySelector('.d-flex.flex-column.gap-1').append(periodeElement);
            }

            const profileContent = document.querySelector('#profile-content');
            profileContent.style.pointerEvents = 'none';
            profileContent.style.opacity = 0;
            profileContent.style.maxHeight = '10vh';
            profileContent.style.overflow = 'auto';


            const collapse = new coreui.Collapse(keahlianCollapse, {
                toggle: false
            })
            collapse.show();
        }

        const keahlianElement = document.querySelectorAll('._keahlian');
        keahlianElement.forEach(element => {
            if (element.children.length === 0) {
                element.innerHTML = '<p class="mb-0 text-muted fw-bold small">Tidak ada</p>';
            }
        });
    </script>
@endsection
