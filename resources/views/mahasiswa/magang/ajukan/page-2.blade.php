<div class="container-lg flex-fill d-flex flex-column mx-auto overflow-auto" style="max-height: calc(100vh - 235px);">
    <h4 class="fw-bold mb-3">Form Pengajuan</h4>
    <div class="d-flex flex-column gap-2 card p-3 mb-4">
        <p class="mb-1">*Dokumen CV akan diambil dari profil Anda</p>
        @if ($user->file_cv)
            <div class="accordion" id="accordion-cv">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse"
                            data-coreui-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Lihat CV Anda
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-coreui-parent="#accordion-cv">
                        <div class="accordion-body">
                            <iframe id="iframe-dokumen-cv" class="w-100" style="height: calc(100vh - 200px);"
                                src="{{ $user->file_cv ? asset($user->file_cv) : '' }}" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <h3 class="">Upload CV Anda terlebih dahulu pada halaman <a href="{{ url('/mahasiswa/dokumen') }}">Dokumen</a></h3>
        @endif

        <div class="d-flex flex-column gap-2 @if (!$user->file_cv) d-none @endif">
            <div class="d-flex flex-column gap-1">
                <label for="catatan" class="form-label fw-bold">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
            </div>
            <p class="fw-bold mb-1">Dokumen Pendukung</p>
            <label for="dokumen_input[]" id="drop-zone" class="text-center mb-3" style="cursor: pointer;">
                <svg class="text-primary" style="width: 4rem; height: 4rem;">
                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-cloud-upload') }}">
                    </use>
                </svg>
                <h4 class="mb-1 fw-bolder">Upload file disini</h4>
                <input class="d-none form-control" type="file" id="dokumen_input[]" name="dokumen_input[]"
                    accept=".pdf" />
            </label>

            <form id="file-input-group" class="mt-2 d-flex flex-column gap-2" method="POST"
                enctype="multipart/form-data">
                @csrf

            </form>
        </div>
    </div>
</div>
