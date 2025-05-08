<div class="d-flex flex-column gap-3 flex-fill">
    <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Pengalaman</h4>
        <button type="button" class="btn btn-primary" onClick="addPengalaman()">
            <svg class="nav-icon" style="width: 20px; height: 20px;">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-plus') }}"></use>
            </svg>
            Tambah Pengalaman
        </button>
    </div>
    <div class="card w-100">
        <div class="card-header">
            <h6 class="fw-bold pb-0 mb-0">Kerja</h6>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-1 flex-fill" id="group-kerja">
                @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'kerja') as $key => $pengalaman)
                    <div class="d-flex flex-column gap-1 flex-fill" style="cursor: pointer;"
                        onClick="editPengalaman(this)">
                        <h7 class="fw-bold mb-0" id="display-nama_pengalaman">{{ $pengalaman->nama_pengalaman }}</h7>
                        <p class="mb-0" id="display-deskripsi_pengalaman">{{ $pengalaman->deskripsi_pengalaman }}</p>
                        <input type="hidden" name="nama_pengalaman[]" value="{{ $pengalaman->nama_pengalaman }}">
                        <input type="hidden" name="deskripsi_pengalaman[]"
                            value="{{ $pengalaman->deskripsi_pengalaman }}">
                        <input type="hidden" name="tag[]"
                            value="{{ implode(', ', $pengalaman->pengalamanTag->pluck('keahlian.nama_keahlian')->toArray()) }}">
                        <input type="hidden" name="tipe_pengalaman[]" value="{{ $pengalaman->tipe_pengalaman }}">
                        <input type="hidden" name="periode_mulai[]" value="{{ $pengalaman->periode_mulai }}">
                        <input type="hidden" name="periode_selesai[]" value="{{ $pengalaman->periode_selesai }}">
                        <input type="file" class="d-none" name="dokumen_file[]">
                        <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
                        <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                            @foreach ($pengalaman->pengalamanTag as $tag)
                                <span
                                    class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if (!$loop->last)
                        <hr class="my-2">
                    @endif
                @empty
                    <p class="mb-0 _tidakada">Tidak ada</p>
                @endforelse
            </div>
        </div>
        <div class="card-header">
            <h6 class="fw-bold pb-0 mb-0">Lomba</h6>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-1 flex-fill" id="group-lomba">
                @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'lomba') as $key => $pengalaman)
                    <div class="d-flex flex-column gap-1 flex-fill" style="cursor: pointer;"
                        onClick="editPengalaman(this)">
                        <h7 class="fw-bold mb-0" id="display-nama_pengalaman">{{ $pengalaman->nama_pengalaman }}</h7>
                        <p class="mb-0" id="display-deskripsi_pengalaman">{{ $pengalaman->deskripsi_pengalaman }}</p>
                        <input type="hidden" name="nama_pengalaman[]" value="{{ $pengalaman->nama_pengalaman }}">
                        <input type="hidden" name="deskripsi_pengalaman[]"
                            value="{{ $pengalaman->deskripsi_pengalaman }}">
                        <input type="hidden" name="tag[]"
                            value="{{ implode(', ', $pengalaman->pengalamanTag->pluck('keahlian.nama_keahlian')->toArray()) }}">
                        <input type="hidden" name="tipe_pengalaman[]" value="{{ $pengalaman->tipe_pengalaman }}">
                        <input type="hidden" name="periode_mulai[]" value="{{ $pengalaman->periode_mulai }}">
                        <input type="hidden" name="periode_selesai[]" value="{{ $pengalaman->periode_selesai }}">
                        <input type="file" class="d-none" name="dokumen_file[]">
                        <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
                        <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                            @foreach ($pengalaman->pengalamanTag as $tag)
                                <span
                                    class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                            @endforeach
                        </div>
                    </div>
                    @if (!$loop->last)
                        <hr class="my-2">
                    @endif
                @empty
                    <p class="mb-0 _tidakada">Tidak ada</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pengalaman" tabindex="-1">
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
                    data-coreui-dismiss="modal" id="btn-hapus-pengalaman">Hapus</button>
                <div>
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" data-coreui-dismiss="modal"
                        id="btn-true-pengalaman">Simpan</button>
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
            pengalaman.remove();
            const groupKerja = document.querySelector('#group-kerja');
            const groupLomba = document.querySelector('#group-lomba');
            if (groupKerja.children.length === 0) {
                groupKerja.insertAdjacentHTML('afterbegin', '<p class="mb-0 _tidakada">Tidak ada</p>');
            }
            if (groupLomba.children.length === 0) {
                groupLomba.insertAdjacentHTML('afterbegin', '<p class="mb-0 _tidakada">Tidak ada</p>');
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
            const eventInput = event.target.querySelector(`input[name="${name}"]`);

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
                input.value = eventInput.value;
            }

            const displayElement = target.querySelector(`#display-${name.replace('[]', '')}`);
            if (displayElement) {
                displayElement.innerHTML = eventInput.value;
            }
        });

        const displayTagElement = target.querySelector('#display-tag');
        if (displayTagElement) {
            displayTagElement.innerHTML = '';
            const eventTags = event.target.querySelectorAll('input[name="tag[]"]');

            const tagValues = eventTags[0].value.split(',');
            tagValues.forEach(tagValue => {
                const tagName = tagValue.trim();
                displayTagElement.innerHTML +=
                    `<span class="badge badge-sm bg-info _badge_keahlian">${tagName}</span>`;
            });
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
                    if (input.type !== 'file') {
                        input.value = pengalaman.querySelector(`input[name="${name}"]`).value;
                    }
                }
            });
        }

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
                if (typeof callback === 'function')
                    callback({
                        target: modalBody.cloneNode(true)
                    });
            };
            const buttonPreviewFile = modalElement.querySelector('#button-preview-file');
            const link = modalElement.querySelector('#path_file_modal').textContent;
            if(link || modalElement.querySelector('#path_file').value) buttonPreviewFile.style.display = 'block';
            modalElement.querySelector('#path_file').addEventListener('change', function() {
                if(this.value) buttonPreviewFile.style.display = 'block';
                else buttonPreviewFile.style.display = 'none';
            });
            buttonPreviewFile.addEventListener('click', function() {
                const file = modalElement.querySelector('#path_file').files[0];
                if (file) {
                    window.open(URL.createObjectURL(file), '_blank');
                } else {                    
                    if (link) {
                        window.open(link, '_blank');
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

                const groupKerja = document.querySelector('#group-kerja');
                const groupLomba = document.querySelector('#group-lomba');
                document.querySelectorAll('#group-kerja input[name="tipe_pengalaman[]"][value="lomba"]')
                    .forEach(
                        el => {
                            const parentElement = el.closest('.d-flex.flex-column.gap-1.flex-fill');
                            if (groupLomba.children.length > 0) {
                                groupLomba.insertAdjacentHTML('beforeend', '<hr class="my-2">');
                            }
                            groupLomba.appendChild(parentElement);
                            if (groupKerja.children.length > 0 && groupKerja.children[0].tagName == 'HR') {
                                groupKerja.removeChild(groupKerja.children[0]);
                            }
                        });
                document.querySelectorAll('#group-lomba input[name="tipe_pengalaman[]"][value="kerja"]')
                    .forEach(
                        el => {
                            const parentElement = el.closest('.d-flex.flex-column.gap-1.flex-fill');
                            if (groupKerja.children.length > 0) {
                                groupKerja.insertAdjacentHTML('beforeend', '<hr class="my-2">');
                            }
                            groupKerja.appendChild(parentElement);
                            if (groupLomba.children.length > 0 && groupLomba.children[0].tagName == 'HR') {
                                groupLomba.removeChild(groupLomba.children[0]);
                            }
                        });
            }, target);
    }

    const addPengalaman = () => {
        openPengalaman((event) => {
            const pengalamanElement = document.createElement('div');
            pengalamanElement.classList.add('d-flex', 'flex-column', 'gap-1', 'flex-fill');
            pengalamanElement.style.cursor = 'pointer';
            pengalamanElement.addEventListener('click', (event) => editPengalaman(pengalamanElement));

            pengalamanElement.innerHTML = `
            <h7 class="fw-bold mb-0" id="display-nama_pengalaman"></h7>
            <p class="mb-0" id="display-deskripsi_pengalaman"></p>
            <input type="hidden" name="nama_pengalaman[]" value="">
            <input type="hidden" name="deskripsi_pengalaman[]" value="">
            <input type="hidden" name="tag[]" value="">
            <input type="hidden" name="tipe_pengalaman[]" value="">
            <input type="hidden" name="periode_mulai[]" value="">
            <input type="hidden" name="periode_selesai[]" value="">
            <input type="file" class="d-none" name="dokumen_file[]">
            <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
            </div>`;

            const tipePengalaman = event.target.querySelector('input[name="tipe_pengalaman"]:checked')
                .value;
            const pengalamanContainer = document.querySelector(`#group-${tipePengalaman}`);
            const tidakAdaElement = pengalamanContainer.querySelector('._tidakada');
            if (tidakAdaElement) {
                tidakAdaElement.remove();
            }

            if (pengalamanContainer.children.length > 0) {
                pengalamanContainer.insertAdjacentHTML('beforeend', '<hr class="my-2">');
            }

            pengalamanContainer.appendChild(pengalamanElement);

            savePengalaman(event, pengalamanContainer.lastElementChild);
        });
    }
</script>
