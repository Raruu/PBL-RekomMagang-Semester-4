@if ($dosen)
    <div class="card-body d-flex flex-column gap-2 flex-fill display-detail" style="opacity: 0">
        <div class="d-flex flex-row gap-3 flex-fill">
            <div for="profile_picture" class="position-relative"
                style="width: 90px; height: 90px; clip-path: circle(50% at 50% 50%);">
                <img src="{{ $dosen->foto_profil == null ? asset('imgs/profile_placeholder.webp') : asset($dosen->foto_profil) }}?{{ now() }}"
                    alt="Profile Picture" class="w-100" id="picture-display">
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
            <div class="d-flex flex-column">
                <p class="fw-bold mb-0">{{ $dosen->nama }}</p>
                <p class="mb-0 text-muted">{{ $dosen->nip }}</p>
                <p class="fw-bold mb-0">{{ $dosen->programStudi->nama_program }}</p>
            </div>
        </div>
        <div class="d-flex flex-row gap-3 flex-fill mt-2">
            <div class="flex-fill">
                <div class="mb-3">
                    <h5 class="card-title">Email</h5>
                    <p class="card-text">{{ $dosen->user->email }}</p>
                </div>
            </div>
            <div class="flex-fill">
                <div class="mb-3">
                    <h5 class="card-title">Nomor Telepon</h5>
                    <p class="card-text">{{ $dosen->nomor_telepon }}</p>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <h5 class="card-title">Alamat</h5>
            <a class="card-text mt-0"
                href="https://maps.google.com/?q={{ $dosen->lokasi->latitude }},{{ $dosen->lokasi->longitude }}"
                target="_blank">
                {{ $dosen->lokasi->alamat }}
            </a>
        </div>
        <div class="mb-3">
            <h5 class="card-title">Minat Penelitian</h5>
            <p class="card-text">{{ $dosen->minat_penelitian }}</p>
        </div>
    </div>
@endif
