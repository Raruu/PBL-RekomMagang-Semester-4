<script>
    const lowonganOpenModalPreviewPdf = (link, name) => {
        const modalElement = document.querySelector('#modal-pdf-preview');
        const modal = new coreui.Modal(modalElement);
        modalElement.querySelector('.modal-title').textContent = `Dokumen Pendukung: ${name}`;
        const iframe = modalElement.querySelector('.pdf_preview');
        iframe.src = link;
        modal.show();
    };

    const initLowongan = () => {
        const initModalCatatanAdmin = () => {
            const modalElement = document.querySelector('#modal-catatan');
            const modal = new coreui.Modal(modalElement);
            const btnCatatan = document.querySelector('.btn_catatan');
            btnCatatan.onclick = () => {
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');
                btnTrue.onclick = () => {
                    btnSpinerFuncs.spinBtnSubmit(modalElement);
                    const form = modalElement.querySelector('form');
                    const data = new FormData(form);
                    axios.post(form.action, new FormData(form))
                        .then(response => {
                            Swal.fire('Berhasil', response.data.message,
                                'success').then(() => {
                                btnSpinerFuncs.resetBtnSubmit(modalElement);
                                const field = document.querySelector('.dokumen_field');
                                field.querySelector('textarea[name="catatan_admin"]')
                                    .value = data
                                    .get('catatan_admin');
                                modal.hide();
                                // window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error updating data:', error);
                            Swal.fire(`Error ${error.status}`, error.response.data.message,
                                'error');
                        });
                };
                btnFalse.onclick = () => {
                    btnSpinerFuncs.resetBtnSubmit(modalElement);
                    modal.hide();
                };
                modal.show();
            };
        };
        initModalCatatanAdmin();
    };
</script>
