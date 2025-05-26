@extends('layouts.app')
@section('title', 'Feedback SPK')
@section('content')
    @vite(['resources/css/mhs-feedback.css'])
    <div class="d-flex flex-column gap-4 pb-4 position-relative">
        <h4 class="fw-bold mb-3">Feedback Sistem Pendukung Keputusan</h4>
        <div class="card d-flex flex-column gap-2 flex-fill p-3">
            <form action="" method="POST" class="d-flex flex-column gap-2 flex-fill" id="form-feedback">
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
                    <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                    <div id="error-komentar" class="text-danger"></div>
                </div>

                <button class="btn btn-primary btn-lg mt-3" type="button" id="btn-submit">
                    <x-btn-submit-spinner wrapWithButton="false">
                        Simpan Feedback
                    </x-btn-submit-spinner>
                </button>
            </form>
        </div>
    </div>
    <script>
        const initFeedback = async () => {
            const formFeedback = document.querySelector('#form-feedback');
            const ratingLabel = document.querySelector('#rating-label');
            const rating = document.querySelector('#rating');

            const changeRating = (ratingValue) => {
                const emojis = formFeedback.querySelectorAll('.emoji');
                emojis.forEach((emoji) => {
                    emoji.classList.remove('active');
                    if (emoji.dataset.value == ratingValue) {
                        emoji.classList.add('active');
                        ratingLabel.textContent = `Rating: ${ratingValue}`;
                    }
                });
            };

            rating.addEventListener('input', () => {
                changeRating(rating.value);
            });
            const emojis = formFeedback.querySelectorAll('.emoji');
            emojis.forEach((emoji) => {
                emoji.addEventListener('click', () => {
                    rating.value = emoji.dataset.value;
                    changeRating(rating.value);
                });
            });

            // const response = await fetch(
            //     "");
            // const data = (await response.json()).data;
            const inputRating = formFeedback.querySelector('#rating');
            const inputKomentar = formFeedback.querySelector('#komentar');
            // if (data) {
            //     inputRating.value = data.rating ?? '3';
            //     inputKomentar.value = data.komentar ?? '';
            // }
            changeRating(inputRating.value);

            const btnSubmit = formFeedback.querySelector('#btn-submit');
            const btnSubmitText = btnSubmit.querySelector('#btn-submit-text');
            const btnSpiner = btnSubmit.querySelector('#btn-submit-spinner');
            const resetBtn = () => {
                btnSubmit.disabled = false;
                btnSubmitText.classList.remove('d-none');
                btnSpiner.classList.add('d-none');
            }
            const prefixError = [];
            btnSubmit.onclick = () => {
                btnSubmit.disabled = true;
                btnSubmitText.classList.add('d-none');
                btnSpiner.classList.remove('d-none');
                $.ajax({
                    url: formFeedback.action,
                    type: formFeedback.method,
                    data: new FormData(formFeedback),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        prefixError.forEach(prefix => {
                            $('#error-' + prefix).text('');
                        });

                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            resetBtn();
                            prefixError.length = 0;
                        });

                    },
                    error: function(response) {
                        console.log(response.responseJSON);
                        resetBtn();
                        prefixError.length = 0;
                        Swal.fire(`Gagal ${response.status}`, response.responseJSON.message,
                            'error');
                        $.each(response.responseJSON.msgField, function(prefix, val) {
                            prefixError.push(prefix);
                            $('#error-' + prefix).text(val[0]);
                        });

                    }
                });
            };
        };
        document.addEventListener('DOMContentLoaded', initFeedback);
    </script>
@endsection
