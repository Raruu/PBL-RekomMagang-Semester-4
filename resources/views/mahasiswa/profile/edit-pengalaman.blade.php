<div class="d-flex flex-column gap-3 flex-fill w-100" style="overflow-x: hidden; ">
    <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Pengalaman</h4>
        <button type="button" class="btn btn-primary" onClick="addPengalaman()">
            <i class="fas fa-plus" style="width: 20px; height: 20px;"></i>
            Tambah Pengalaman
        </button>
    </div>
    <p class="mb-0 text-muted">Jika ada nama yang sama maka akan dianggap sebagai pengalaman yang sama (yang lama akan
        tetap disimpan)</p>
    <div class="card w-100">
        <div class="card-header">
            <h6 class="fw-bold pb-0 mb-0">Kerja</h6>
        </div>
        <div class="card-body p-0">
            <div class="d-flex flex-column gap-0 flex-fill" id="group-kerja">
                @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'kerja') as $key => $pengalaman)
                    @include('mahasiswa.profile.edit-pengalaman-card')
                    @if (!$loop->last)
                        <hr class="my-0">
                    @endif
                @empty
                    <p class="mb-0 _tidakada p-3">Tidak ada</p>
                @endforelse
            </div>
        </div>
        <div class="card-header">
            <h6 class="fw-bold pb-0 mb-0">Lomba</h6>
        </div>
        <div class="card-body p-0">
            <div class="d-flex flex-column gap-0 flex-fill" id="group-lomba">
                @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'lomba') as $key => $pengalaman)
                    @include('mahasiswa.profile.edit-pengalaman-card')
                    @if (!$loop->last)
                        <hr class="my-0">
                    @endif
                @empty
                    <p class="mb-0 _tidakada p-3">Tidak ada</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-lg" id="modal-pengalaman" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pengalaman</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer d-flex flex-row justify-content-between">
                <button type="button" class="btn btn-danger"
                    style="transition: opacity 0.5s ease-in-out; opacity: 0; pointer-events: none;"
                    data-coreui-dismiss="modal" id="btn-hapus-pengalaman"><i class="fas fa-trash"></i> Hapus</button>
                <div>
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal"><i
                            class="fas fa-times"></i> Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-true-pengalaman"> <i class="fas fa-save"></i>
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.modal-yes-no')

<script>
    const hapusPengalamanAsk = (pengalaman) => {
        const modalElement = document.getElementById('modal-yes-no');
        const modalBody = modalElement.querySelector(".modal-body");
        modalBody.innerHTML = `Apakah anda yakin ingin menghapus pengalaman ini?`;

        const btnTrue = modalElement.querySelector("#btn-true-yes-no");
        btnTrue.onclick = () => {
            modalBody.innerHTML = ``;
            const pengalamanContainer = pengalaman.parentElement;
            const pengalamanIndex = Array.from(pengalamanContainer.children).indexOf(pengalaman);
            pengalaman.remove();
            if (pengalamanContainer.children[pengalamanIndex - 1]?.tagName === 'HR') {
                pengalamanContainer.removeChild(pengalamanContainer.children[pengalamanIndex - 1]);
            }
            if (pengalamanContainer.children[0]?.tagName === 'HR') {
                pengalamanContainer.removeChild(pengalamanContainer.children[0]);
            }



            const groupKerja = document.querySelector('#group-kerja');
            const groupLomba = document.querySelector('#group-lomba');
            if (groupKerja.children.length === 0) {
                groupKerja.insertAdjacentHTML('afterbegin', '<p class="mb-0 _tidakada p-3">Tidak ada</p>');
            }
            if (groupLomba.children.length === 0) {
                groupLomba.insertAdjacentHTML('afterbegin', '<p class="mb-0 _tidakada p-3">Tidak ada</p>');
            }
        };

        const modal = new coreui.Modal(modalElement);
        modal.show();
    };

    const savePengalaman = (event, target) => {
        const targetInputs = target.querySelectorAll('input, textarea, select');
        let tipe_pengalaman_checked = false;
        targetInputs.forEach((input) => {
            const name = input.getAttribute('name');
            const eventInput = event.target.querySelector(
                `textarea[name="${name}"], input[name="${name}"]`);

            if (input.type === 'file') {
                const eventFile = event.target.querySelector(`input[name="${name}"]`);
                const dataTransfer = new DataTransfer();
                if (eventFile.files.length > 0) {
                    dataTransfer.items.add(eventFile.files[0]);
                    input.files = dataTransfer.files;
                }
            } else if (name === 'tipe_pengalaman[]' && !tipe_pengalaman_checked) {
                const eventName = name.replace('[]', '');
                const eventSelect = event.target.querySelectorAll(`input[name="${eventName}"]`);
                eventSelect.forEach((el) => {
                    if (el.checked) {
                        tipe_pengalaman_checked = true;
                        input.value = el.value;
                        // console.log(el.value, input.value, input);
                    }
                });
            } else {
                console.log(input, eventInput);
                input.value = sanitizeString(eventInput.value);
            }

            const displayElement = target.querySelector(`#display-${name.replace('[]', '')}`);
            if (displayElement) {
                displayElement.innerHTML = sanitizeString(eventInput.value);
            }
        });

        const displayTagElement = target.querySelector('#display-tag');
        if (displayTagElement) {
            displayTagElement.innerHTML = '';
            const eventTags = event.target.querySelector('input[name="tag[]"]');

            const eventTagValue = eventTags.value;
            if (eventTagValue != '') {
                const tagValues = JSON.parse(eventTagValue);
                tagValues.forEach(tag => {
                    const tagName = tag.value;
                    displayTagElement.innerHTML +=
                        `<span class="badge badge-sm bg-info _badge_keahlian">${tagName}</span>`;
                });
            }

        }
    }

    const openPengalaman = (callback, pengalaman) => {
        const modalElement = document.getElementById('modal-pengalaman');
        const btnTrue = modalElement.querySelector("#btn-true-pengalaman");
        const modalBody = modalElement.querySelector(".modal-body");
        modalBody.innerHTML = `@include('mahasiswa.profile.edit-pengalaman-modal-form')`;

        if (typeof pengalaman !== 'undefined') {
            const link = modalElement.querySelector('#path_file_modal').textContent = pengalaman.querySelector(
                '#path_file').textContent;
            const inputs = modalBody.querySelectorAll('input, textarea, select');
            inputs.forEach((input) => {
                const name = input.getAttribute('name');
                if (name === 'tipe_pengalaman') {
                    const target = pengalaman.querySelector(`input[name="${name}[]"]`);
                    modalBody.querySelectorAll('input[name="tipe_pengalaman"]').forEach(el => {
                        if (el.value === target.value) {
                            el.checked = true;
                        } else {
                            el.checked = false;
                        }
                    });
                } else {
                    if (input.type === 'file') {
                        input.files = pengalaman.querySelector(`input[name="${name}"]`).files;
                    } else {
                        input.value = pengalaman.querySelector(`input[name="${name}"]`).value;
                    }
                }
            });
        }

        const tagify = new Tagify(modalBody.querySelector('input[name="tag[]"]'), {
            enforceWhitelist: true,
            whitelist: @json($keahlian->pluck('nama_keahlian')->toArray()),
            dropdown: {
                position: "input",
                maxItems: Infinity,
                enabled: 0,
            },
            templates: {
                dropdownItemNoMatch() {
                    return `Nothing Found`;
                }
            },
            enforceWhitelist: true,
        });

        const tipePengalaman = modalBody.querySelectorAll('input[name="tipe_pengalaman"]');
        const switchMoreInformation = (event) => {
            const target = event.target;
            if (target.value === 'kerja') {
                document.getElementById('input-tanggal-kerja').style.display = 'block';
                document.getElementById('input-file-lomba').style.display = 'none';
            } else {
                document.getElementById('input-tanggal-kerja').style.display = 'none';
                document.getElementById('input-file-lomba').style.display = 'block';
            }
        };
        switchMoreInformation({
            target: document.querySelector('input[name="tipe_pengalaman"]:checked')
        });
        tipePengalaman.forEach(el => {
            el.addEventListener('change', switchMoreInformation);
        });

        const shownHandler = (event) => {
            const btnHapus = modalElement.querySelector("#btn-hapus-pengalaman");
            if (pengalaman) {
                btnHapus.style.pointerEvents = '';
                btnHapus.style.opacity = '1';
                btnHapus.onclick = () => {
                    hapusPengalamanAsk(pengalaman);
                };
            } else {
                btnHapus.style.pointerEvents = 'none';
                btnHapus.style.opacity = '0';
            }
            btnTrue.onclick = () => {
                const nameRequired = [{
                    name: 'nama_pengalaman[]',
                    length: 255
                }, {
                    name: 'deskripsi_pengalaman[]'
                }];
                const tipePengalaman = modalBody.querySelectorAll('input[name="tipe_pengalaman"]');

                if (tipePengalaman[0].checked) {
                    nameRequired.push({
                        name: 'periode_mulai[]'
                    });
                    nameRequired.push({
                        name: 'periode_selesai[]'
                    });
                } else {
                    nameRequired.push({
                        name: 'dokumen_file[]'
                    });
                }

                // VALIDATE
                let isValid = true;
                nameRequired.forEach(nameRequiredValue => {
                    const name = nameRequiredValue.name;
                    const length = nameRequiredValue.length;

                    const input = modalBody.querySelector(
                        `textarea[name="${name}"], input[name="${name}"]`);
                    if (input.type === 'file' && input.files.length > 0) {
                        if (input.files[0].size > 2097152) {
                            input.classList.add('is-invalid');
                            const errorElement = modalBody.querySelector(
                                `#error-${name.replace('[]', '')}`);
                            errorElement.innerHTML = `Ukuran file tidak boleh lebih dari 2MB`;
                            isValid = false;
                            return;
                        }
                    }
                    if (input && input.value === '') {
                        const buttonPreviewFile = modalElement.querySelector(
                            '#button-preview-file');
                        if (input.type === 'file' && buttonPreviewFile) {
                            if (buttonPreviewFile.style.display === 'block') {
                                return;
                            }
                        };

                        input.classList.add('is-invalid');
                        const errorElement = modalBody.querySelector(
                            `#error-${name.replace('[]', '')}`);
                        errorElement.innerHTML = `Field ini tidak boleh kosong`;
                        isValid = false;
                    } else if (length && input.value.length + 1 > length) {
                        input.classList.add('is-invalid');
                        const errorElement = modalBody.querySelector(
                            `#error-${name.replace('[]', '')}`);
                        errorElement.innerHTML = `Maksimal ${length} karakter`;
                        isValid = false;
                    } else if (name === 'periode_selesai[]') {
                        const periode_mulai = modalBody.querySelector(
                            `input[name="periode_mulai[]"]`).value;
                        const periode_selesai = modalBody.querySelector(
                            `input[name="periode_selesai[]"]`).value;
                     
                        const dateMulai = new Date(periode_mulai);
                        const dateSelesai = new Date(periode_selesai);
                        dateSelesai.setDate(dateSelesai.getDate() - 1);
                        if (dateMulai > dateSelesai) {
                            input.classList.add('is-invalid');
                            const errorElement = modalBody.querySelector(
                                `#error-${name.replace('[]', '')}`);
                            errorElement.innerHTML =
                                `Periode selesai harus lebih besar dari periode mulai`;
                            isValid = false;
                        }
                    } else {
                        input.classList.remove('is-invalid');
                        const errorElement = modalBody.querySelector(
                            `#error-${name.replace('[]', '')}`);
                        errorElement.innerHTML = ``;
                    }
                });


                if (isValid) {
                    if (typeof callback === 'function')
                        callback({
                            target: modalBody.cloneNode(true)
                        });
                    modal.hide();
                }
            };
            const buttonPreviewFile = modalElement.querySelector('#button-preview-file');
            const link = modalElement.querySelector('#path_file_modal').textContent;
            if (link || modalElement.querySelector('#path_file').value) buttonPreviewFile.style.display =
                'block';
            modalElement.querySelector('#path_file').addEventListener('change', function() {
                if (this.value) buttonPreviewFile.style.display = 'block';
                else buttonPreviewFile.style.display = 'none';
            });
            buttonPreviewFile.addEventListener('click', function() {
                const file = modalElement.querySelector('#path_file').files[0];
                if (file) {
                    openModalPreviewPdf(URL.createObjectURL(file), file.name);
                } else {
                    if (link) {
                        openModalPreviewPdf(link, modalBody.querySelector('#nama_pengalaman').value);
                    }
                }
            });
        };

        const hiddenHandler = () => {
            tipePengalaman.forEach(el => {
                el.removeEventListener('change', switchMoreInformation);
            });
            const btnHapus = modalElement.querySelector("#btn-hapus-pengalaman");
            btnHapus.style.pointerEvents = 'none';
            btnHapus.style.opacity = '0';

            modalBody.innerHTML = "";
            modalElement.removeEventListener('shown.coreui.modal', shownHandler);
            modalElement.removeEventListener('hidden.coreui.modal', hiddenHandler);
        };

        modalElement.addEventListener('shown.coreui.modal', shownHandler);
        modalElement.addEventListener('hidden.coreui.modal', hiddenHandler);

        const modal = new coreui.Modal(modalElement);
        modal.show();
    };

    const editPengalaman = (target) => {
        openPengalaman(
            (event) => {
                savePengalaman(event, target);

                // MOVE PENGALAMAN
                const groupKerja = document.querySelector('#group-kerja');
                const groupLomba = document.querySelector('#group-lomba');

                function moveElements(sourceSelector, targetGroup, otherGroup, emptyMessage) {
                    document.querySelectorAll(sourceSelector).forEach(el => {
                        if (targetGroup.children[0]?.tagName === 'HR' || targetGroup.children[0]
                            ?.tagName === 'P') {
                            targetGroup.innerHTML = '';
                        }

                        const parentElement = el.closest('.d-flex.flex-column.gap-1.flex-fill');
                        if (targetGroup.children.length > 0) {
                            targetGroup.insertAdjacentHTML('beforeend', '<hr class="my-0">');
                        }
                        targetGroup.appendChild(parentElement);
                        if (otherGroup.children[0]?.tagName === 'HR') {
                            otherGroup.removeChild(otherGroup.children[0]);
                        }
                        if (otherGroup.children.length === 0) {
                            otherGroup.insertAdjacentHTML('afterbegin',
                                `<p class="mb-0 _tidakada p-3">${emptyMessage}</p>`);
                        }
                    });
                }

                moveElements(
                    '#group-kerja input[name="tipe_pengalaman[]"][value="lomba"]',
                    groupLomba,
                    groupKerja,
                    'Tidak ada'
                );
                moveElements(
                    '#group-lomba input[name="tipe_pengalaman[]"][value="kerja"]',
                    groupKerja,
                    groupLomba,
                    'Tidak ada'
                );
            }, target);
    }

    const addPengalaman = () => {
        openPengalaman((event) => {
            const pengalamanElement = document.createElement('div');
            pengalamanElement.classList.add('d-flex', 'flex-column', 'gap-1', 'flex-fill',
                'background-hoverable', 'p-3');
            pengalamanElement.style.cursor = 'pointer';
            pengalamanElement.addEventListener('click', (event) => editPengalaman(pengalamanElement));

            pengalamanElement.innerHTML = `@include('mahasiswa.profile.edit-pengalaman-add')`;

            const tipePengalaman = event.target.querySelector('input[name="tipe_pengalaman"]:checked')
                .value;
            const pengalamanContainer = document.querySelector(`#group-${tipePengalaman}`);
            const tidakAdaElement = pengalamanContainer.querySelector('._tidakada');
            if (tidakAdaElement) {
                tidakAdaElement.remove();
            }

            if (pengalamanContainer.children.length > 0) {
                pengalamanContainer.insertAdjacentHTML('beforeend', '<hr class="my-0">');
            }

            pengalamanContainer.appendChild(pengalamanElement);

            savePengalaman(event, pengalamanContainer.lastElementChild);
        });
    }
</script>
