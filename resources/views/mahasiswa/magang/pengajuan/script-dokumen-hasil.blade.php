<script>
    const initDokumenHasil = () => {
        const displayDetail = document.querySelector('.display-detail');

        $("#form-dokumen").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href =
                                `${window.location.pathname}?open=3`;
                        });
                    },
                    error: function(response) {
                        console.log(response.responseJSON);
                        btnSpinerFuncs.resetBtnSubmit(displayDetail);
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        btnSpinerFuncs.resetBtnSubmit(displayDetail);
                    }
                });
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        const uploadButton = displayDetail.querySelector('#upload-button');

        uploadButton.onclick = () => {
            setTimeout(() => {
                btnSpinerFuncs.spinBtnSubmit(displayDetail);

            }, 1);
        };

        const iframe = displayDetail.querySelector('.iframe_file_sertifikat');
        const fileInput = displayDetail.querySelector('#file_sertifikat');
        fileInput.addEventListener('change', function() {
            const disabled = !this.files.length;
            uploadButton.disabled = disabled;
            if (disabled) {
                uploadButton.classList.add('btn-secondary');
                uploadButton.classList.remove('btn-primary');
            } else {
                uploadButton.classList.add('btn-primary');
                uploadButton.classList.remove('btn-secondary');
            }
        })
        fileInput.addEventListener('change', function() {
            const files = this.files;
            const file = files[0];
            if (files.length > 0) {
                const fileUrl = URL.createObjectURL(file);
                iframe.src = fileUrl;
                iframe.classList.remove('d-none');
            }
        });
    };
</script>
