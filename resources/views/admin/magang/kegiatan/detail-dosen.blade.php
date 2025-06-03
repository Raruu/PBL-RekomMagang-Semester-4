<div class="card-body d-flex flex-column gap-2 flex-fill display-detail display_dosen justify-content-center align-items-center"
    style="opacity: 0">
    <div>
        <h4 class="fw-bold mb-0 pilih_dosen"><span class="text-muted">Preview profil Dosen:</span> Pilih Dosen terlebih
            dahulu</h4>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column d-none" id="fetch-loading">
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"></div>
        <p>Fetching data...</p>
    </div>
    <div class="d-flex flex-row text-start align-items-start gap-4 w-100 d-none display_dosen_content"
        style="height: fit-content; width: fit-content;">
        <div class="d-flex flex-column gap-3 justify-content-center align-items-center" style="padding-left: 8px;">
            <div for="profile_picture" class="position-relative"
                style="width: 124px; height: 124px; clip-path: circle(50% at 50% 50%);">
                <img src="{{ asset('imgs/profile_placeholder.webp') }}?{{ now() }}" alt="Profile Picture"
                    class="w-100" id="picture-display">
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
                    <p class="fw-bold mb-0 text-wrap dosen_nama" style="font-weight: 500;"> </p>
                    <p class="mb-0 text-muted dosen_nip"></p>
                    <p class="fw-bold mb-0 dosen_program"></p>
                    <div class="flex-fill mt-1">
                        <div class="">
                            <h5 class="card-title mb-1">Minat Penelitian</h5>
                            <p class="card-text dosen_minat"></p>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3 mx-auto">
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Email</h5>
                            <p class="card-text dosen_email"></p>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Nomor Telepon</h5>
                            <p class="card-text dosen_telp"></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
