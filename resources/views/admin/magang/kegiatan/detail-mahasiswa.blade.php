<div class="d-flex flex-column text-start align-items-start p-3 w-100 display-detail" style="opacity: 0">
    <div class="d-flex flex-row text-start align-items-start gap-4 w-100"
        style="height: fit-content; width: fit-content;">
        <div class="d-flex flex-column gap-3 justify-content-center align-items-center" style="padding-left: 8px;">
            <div for="profile_picture" class="position-relative"
                style="width: 124px; height: 124px; clip-path: circle(50% at 50% 50%);">
                <img src="{{ $pengajuanMagang->profilMahasiswa->foto_profil ? asset($pengajuanMagang->profilMahasiswa->foto_profil) : asset('imgs/profile_placeholder.webp') }}?{{ now() }}"
                    alt="Profile Picture" class="w-100 h-100 object-fit-cover" id="picture-display">
                <div class="rounded-circle position-absolute w-100 h-100 bg-black"
                    style="opacity: 0; transition: opacity 0.15s; cursor: pointer; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                    onmouseover="this.style.opacity = 0.5;" onmouseout="this.style.opacity = 0;"
                    onclick="document.getElementById('full-screen-image').style.display = 'flex';
                                document.getElementById('picture-display-full').src = this.parentNode.querySelector('#picture-display').src;">
                    <svg class="position-absolute text-white h-auto"
                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 15%">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-search') }}">
                        </use>
                    </svg>
                </div>
            </div>
            <div id="full-screen-image" class="position-fixed w-100 h-100 justify-content-center align-items-center"
                style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
                onclick="this.style.display = 'none';">
                <img id="picture-display-full" alt="Profile Picture" class="img-fluid"
                    style="max-width: 90%; max-height: 90%;">
            </div>
        </div>
        <div class="d-flex flex-column gap-0 flex-fill">
            <div class="d-flex flex-row  w-100">
                <div class="d-flex flex-column">
                    <p class="fw-bold mb-0 text-wrap" style="font-weight: 500;">
                        {{ $pengajuanMagang->profilMahasiswa->nama }}</p>
                    <p class="mb-0 text-muted">{{ $pengajuanMagang->profilMahasiswa->nim }}</p>
                    <p class="fw-bold mb-0">{{ $pengajuanMagang->profilMahasiswa->programStudi->nama_program }}</p>
                    <p class="fw-bold mb-0"> <span class="text-muted">Angkatan:
                        </span>{{ $pengajuanMagang->profilMahasiswa->angkatan }}
                    </p>
                    <p class="fw-bold mb-0"> <span class="text-muted">IPK Komulatif:
                        </span>{{ $pengajuanMagang->profilMahasiswa->ipk }}
                    </p>
                </div>
                <div class="d-flex flex-column gap-3 mx-auto">
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Email</h5>
                            <p class="card-text">{{ $pengajuanMagang->profilMahasiswa->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Nomor Telepon</h5>
                            <p class="card-text">{{ $pengajuanMagang->profilMahasiswa->nomor_telepon }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-fill mt-0">
                <div class="d-flex flex-row gap-1">
                    <p class="mb-0 text-muted fw-bold">Alamat: </p>
                    <a class="card-text" target="_blank"
                        href="https://maps.google.com/?q={{ $pengajuanMagang->profilMahasiswa->lokasi->latitude }},{{ $pengajuanMagang->profilMahasiswa->lokasi->longitude }}">
                        {{ $pengajuanMagang->profilMahasiswa->lokasi->alamat }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
    <div class="d-flex flex-row w-100">
        <div class="d-flex flex-column w-50">
            <h5 class="fw-bold ">Keahlian Mahasiswa</h5>
            <div class="d-flex flex-column gap-2">
                @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                    @php
                        $filteredKeahlian = $keahlian_mahasiswa->where('tingkat_kemampuan', $keytingkatKemampuan);
                    @endphp

                    <div class="d-flex flex-column {{ $filteredKeahlian->isEmpty() ? 'd-none' : '' }}">
                        <p class="fw-bold mb-0"> &#8226;
                            <span class="title_keahlian_mahasiswa">{{ $tingkatKemampuan }}</span>
                        </p>
                        <div class="d-flex flex-row gap-1 flex-wrap ps-2 _keahlian keahlian_mahasiswa">
                            @foreach ($filteredKeahlian as $keahlianMahasiswa)
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
                @endforeach
            </div>
        </div>
        <div class="d-flex flex-column w-50">
            <h5 class="fw-bold ">Keahlian Lowongan</h5>
            <div class="d-flex flex-column gap-2">
                @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
                    @php
                        $keahlianLowongan = $lowongan->keahlianLowongan->where(
                            'kemampuan_minimum',
                            $keytingkatKemampuan,
                        );
                    @endphp
                    @if (!$keahlianLowongan->isEmpty())
                        <div class="d-flex flex-column">
                            <p class="fw-bold mb-0"> &#8226;
                                <span class="title_keahlian_lowongan">{{ $tingkatKemampuan }}</span>
                            </p>
                            <div class="d-flex flex-row gap-1 flex-wrap ps-2 _keahlian keahlian_lowongan">
                                @foreach ($keahlianLowongan as $keahlianMahasiswa)
                                    <span
                                        class="badge badge-sm bg-secondary">{{ $keahlianMahasiswa->keahlian->nama_keahlian }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
    <div class="d-flex flex-row w-100 gap-3">
        <p class="fw-bold mb-0">
            <span class="text-muted">Jumlah keahlian yang cocok:
            </span>
            <span id="jumlah_keahlian_cocok"></span>
        </p>
    </div>
</div>
