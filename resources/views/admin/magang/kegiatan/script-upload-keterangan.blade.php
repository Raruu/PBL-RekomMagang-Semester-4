<script>
    const initUploadKeterangan = () => {
        const uploadButton = document.querySelector('#upload-button');
        document.getElementById('keterangan_magang').addEventListener('change', function() {
            const disabled = !this.files.length;
            uploadButton.disabled = disabled;
            if (disabled) {
                uploadButton.classList.add('btn-secondary');
                uploadButton.classList.remove('btn-primary');
            } else {
                uploadButton.classList.add('btn-primary');
                uploadButton.classList.remove('btn-secondary');
            }

            const file = document.getElementById('keterangan_magang').files[0];
            const fileUrl = URL.createObjectURL(file);
            const iframe = document.getElementById('iframe-dokumen-keterangan');
            iframe.src = fileUrl;
            iframe.classList.remove('d-none');
            iframe.parentNode.querySelector('h1').classList.add('d-none');
        });

        const hapusSuratKeterangan = () => {
            const btnHapus = document.querySelector("#btn-hapus-keterangan");
            const modalElement = document.querySelector('#modal-hapus');
            const modal = new coreui.Modal(modalElement);

            const btnCancel = modalElement.querySelector('#btn-false-yes-no');
            btnCancel.onclick = () => {
                modal.hide();
            }
            btnHapus.onclick = () => {
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                btnTrue.onclick = () => {
                    $.ajax({
                        url: "{{ route('admin.magang.kegiatan.delete.keterangan') }}",
                        method: 'DELETE',
                        data: {
                            pengajuan_id: "{{ $pengajuan_id }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(response) {
                            console.log(response.responseJSON);
                            resetSpinner();
                            Swal.fire({
                                title: `Gagal ${response.status}`,
                                text: response.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                    modal.hide();
                }
                modal.show();
            };
        };
        hapusSuratKeterangan();

        uploadButton.onclick = () => {
            uploadButton.querySelector('#btn-submit-text').classList.add('d-none');
            uploadButton.querySelector('#btn-submit-spinner').classList.remove('d-none');
        };

        const resetSpinner = () => {
            uploadButton.querySelector('#btn-submit-text').classList.remove('d-none');
            uploadButton.querySelector('#btn-submit-spinner').classList.add('d-none');
        };

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
                            window.location.reload();
                        });
                    },
                    error: function(response) {
                        console.log(response.responseJSON);
                        resetSpinner();
                        Swal.fire({
                            title: `Gagal ${response.status}`,
                            text: response.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
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
    };
</script>
