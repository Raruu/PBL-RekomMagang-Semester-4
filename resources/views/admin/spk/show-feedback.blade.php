<div class="d-flex flex-column text-start align-items-start p-3 w-100 ">
    <div class="d-flex flex-row text-start align-items-start gap-4 w-100"
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
                    <p class="fw-bold mb-0 text-wrap" style="font-weight: 500;">
                        {{ $profilMahasiswa->nama }}
                    </p>
                    <p class="mb-0 text-muted">{{ $profilMahasiswa->nim }}</p>
                    <p class="fw-bold mb-0">{{ $profilMahasiswa->programStudi->nama_program }}</p>
                    <p class="fw-bold mb-0"> <span class="text-muted">Angkatan:
                        </span>{{ $profilMahasiswa->angkatan }}
                    </p>

                </div>
                <div class="d-flex flex-column gap-3 mx-auto">
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Email</h5>
                            <p class="card-text">{{ $profilMahasiswa->email }}</p>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="">
                            <h5 class="card-title mb-1">Nomor Telepon</h5>
                            <p class="card-text">{{ $profilMahasiswa->nomor_telepon }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <hr class="bg-primary border-2 border-top w-100" style="height: 1px;" />
    <div class="d-flex flex-column w-100 gap-3">
        <div class="d-flex flex-column gap-2">
            <label for="rating" class="form-label">Rating</label>
            <input type="number" class="form-control" id="rating" name="rating" placeholder="Rating" readonly
                disabled value="{{ $feedback->rating ?? '' }}">
            <label for="komentar" class="form-label">Komentar</label>
            <textarea class="form-control" id="komentar" name="komentar" rows="3" placeholder="Komentar" readonly disabled>{{ $feedback->komentar ?? '' }}
            </textarea>
        </div>
    </div>
</div>
