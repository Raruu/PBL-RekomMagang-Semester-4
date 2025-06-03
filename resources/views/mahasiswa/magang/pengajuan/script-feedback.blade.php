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

        const response = await fetch(
            "{{ route('mahasiswa.magang.feedback', $pengajuanMagang->pengajuan_id) }}");
        const data = (await response.json()).data;
        const inputRating = formFeedback.querySelector('#rating');
        const inputKomentar = formFeedback.querySelector('#komentar');
        const inputPengalamanBelajar = formFeedback.querySelector('#pengalaman_belajar');
        const inputKendala = formFeedback.querySelector('#kendala');
        const inputSaran = formFeedback.querySelector('#saran');
        if (data) {
            inputRating.value = data.rating ?? '3';
            inputKomentar.value = data.komentar ?? '';
            inputPengalamanBelajar.value = data.pengalaman_belajar ?? '';
            inputKendala.value = data.kendala ?? '';
            inputSaran.value = data.saran ?? '';
            if (data.rating || data.komentar || data.pengalaman_belajar || data.kendala || data.saran) {
                const andaSudahFeedback = document.querySelector('.anda_sudah_feedback');
                if (andaSudahFeedback) andaSudahFeedback.classList.remove('d-none');
                formFeedback.classList.add('opacity-50');
                formFeedback.style.pointerEvents = 'none';
            }
        }
        changeRating(inputRating.value);
        formFeedback.style.opacity = '';

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
                        window.location.href =
                            `${window.location.pathname}?open=4`;
                    });

                },
                error: function(response) {
                    console.log(response.responseJSON);
                    resetBtn();
                    prefixError.length = 0;
                    Swal.fire(`Gagal!`, response.responseJSON.message,
                        'error');
                    $.each(response.responseJSON.msgField, function(prefix, val) {
                        prefixError.push(prefix);
                        $('#error-' + prefix).text(val[0]);
                    });

                }
            });
        };

        const btnEditFeedback = document.querySelector('.btn_edit_feedback');
        btnEditFeedback.onclick = () => {
            const andaSudahFeedback = document.querySelector('.anda_sudah_feedback');
            formFeedback.classList.remove('opacity-50');
            formFeedback.style.pointerEvents = '';
            andaSudahFeedback.classList.add('d-none');
        };
    };
</script>
