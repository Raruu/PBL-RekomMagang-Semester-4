<div class="card-body d-flex flex-column gap-2 flex-fill">
    <div class="alert alert-success anda_sudah_feedback d-none d-flex justify-content-between align-items-center"
        role="alert">
        Anda sudah mengisi feedback.
        <button type="button" class="btn btn-warning btn-sm btn_edit_feedback" title="Edit Feedback">
            <i class="fa-solid fa-pen"></i> Edit
        </button>
    </div>
    <form action="{{ route('mahasiswa.magang.feedback.update', ['pengajuan_id' => $pengajuanMagang->pengajuan_id]) }}"
        method="POST" class="d-flex flex-column gap-2 flex-fill" id="form-feedback" style="opacity: 0">
        @csrf
        @method('PUT')
        <div class="mb-2 text-center">
            <h5 class="form-label fw-bold" id="rating-label">Rating: 3</h5>
            <div class="emoji-rating mb-3 px-2 mt-4 user-select-none">
                <div class="emoji" data-value="1">
                    {{-- <img src="{{ asset('imgs/sk/sk-1.webp') }}" alt="Angry Emoji" class="rounded-circle"
                                width="96" height="96"> --}}
                    <i class="fa-solid fa-face-frown"></i>
                    <span class="">Sangat Tidak Puas</span>
                </div>
                <div class="emoji" data-value="2">
                    {{-- <img src="{{ asset('imgs/sk/sk-2.webp') }}" alt="Frowning Emoji" class="rounded-circle"
                                width="96" height="96"> --}}
                    <i class="fa-solid fa-face-tired"></i>
                    <span class="">Tidak Puas</span>
                </div>
                <div class="emoji active" data-value="3">
                    {{-- <img src="{{ asset('imgs/sk/sk-3.webp') }}" alt="Angry Emoji" class="rounded-circle"
                                width="96" height="96"> --}}
                    <i class="fa-solid fa-face-meh-blank"></i>
                    <span class="">Netral</span>
                </div>
                <div class="emoji" data-value="4">
                    {{-- <img src="{{ asset('imgs/sk/sk-4.webp') }}" alt="Angry Emoji" class="rounded-circle"
                                width="96" height="96"> --}}
                    <i class="fa-solid fa-face-surprise"></i>
                    <span class="">Puas</span>
                </div>
                <div class="emoji" data-value="5">
                    {{-- <img src="{{ asset('imgs/sk/sk-5.webp') }}" alt="Grinning Face Emoji" class="rounded-circle"
                                width="96" height="96"> --}}
                    <i class="fa-solid fa-face-grin-beam"></i>
                    <span class="">Sangat Puas</span>
                </div>
            </div>

            <div class="px-5">
                <input type="range" class="form-range custom-range" min="1" max="5" step="1"
                    id="rating" name="rating" value="3">
                <div id="error-rating" class="text-danger"></div>
            </div>
        </div>
        <div class="d-flex flex-column gap-1">
            <label for="komentar" class="form-label fw-bold">Komentar</label>
            <textarea class="form-control" id="komentar" name="komentar" rows="1" required></textarea>
            <div id="error-komentar" class="text-danger"></div>
        </div>
        <div class="d-flex flex-column gap-1">
            <label for="pengalaman_belajar" class="form-label fw-bold">Pengalaman Belajar</label>
            <textarea class="form-control" id="pengalaman_belajar" name="pengalaman_belajar" rows="1" required></textarea>
            <div id="error-pengalaman_belajar" class="text-danger"></div>
        </div>
        <div class="d-flex flex-column gap-1">
            <label for="kendala" class="form-label fw-bold">Kendala</label>
            <textarea class="form-control" id="kendala" name="kendala" rows="1" required></textarea>
            <div id="error-kendala" class="text-danger"></div>
        </div>
        <div class="d-flex flex-column gap-1">
            <label for="saran" class="form-label fw-bold">Saran</label>
            <textarea class="form-control" id="saran" name="saran" rows="1" required></textarea>
            <div id="error-saran" class="text-danger"></div>
        </div>

        <button class="btn btn-primary btn-lg mt-3" type="button" id="btn-submit">
            <x-btn-submit-spinner wrapWithButton="false">
                Simpan Feedback
            </x-btn-submit-spinner>
        </button>
    </form>
</div>
