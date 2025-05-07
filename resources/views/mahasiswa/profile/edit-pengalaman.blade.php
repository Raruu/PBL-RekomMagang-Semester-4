<div class="d-flex flex-column gap-3 flex-fill">
    <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Pengalaman</h4>
        <button type="button" class="btn btn-primary" onClick="openPengalaman()">
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
            @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'kerja') as $key => $pengalaman)
                <div class="d-flex flex-column gap-1 flex-fill">
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
                        <input type="file" class="d-none" name="dokumen_file[]" >
                        <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                            @foreach ($pengalaman->pengalamanTag as $tag)
                                <span
                                    class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (!$loop->last)
                    <hr class="my-2">
                @endif
            @empty
                <p class="mb-0">Tidak ada</p>
            @endforelse
        </div>
        <div class="card-header">
            <h6 class="fw-bold pb-0 mb-0">Lomba</h6>
        </div>
        <div class="card-body">
            @forelse ($user->pengalamanMahasiswa->where('tipe_pengalaman', 'lomba') as $key => $pengalaman)
                <div class="d-flex flex-column gap-1 flex-fill">
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
                        <input type="file" class="d-none" name="dokumen_file[]" >
                        <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                            @foreach ($pengalaman->pengalamanTag as $tag)
                                <span
                                    class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (!$loop->last)
                    <hr class="my-2">
                @endif
            @empty
                <p class="mb-0">Tidak ada</p>
            @endforelse
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" data-coreui-dismiss="modal"
                    id="btn-true-pengalaman">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const editPengalaman = (target) => {
        openPengalaman(
            (event) => {
                // console.log(event.target);
                // console.log(target);
                const targetInputs = target.querySelectorAll('input, textarea, select');
                targetInputs.forEach((input) => {
                    const name = input.getAttribute('name');
                    const eventInput = event.target.querySelector(`input[name="${name}"]`);
                    // console.log(eventInput);
                    // console.log(input);
                    input.value = eventInput.value;
                    const displayElement = target.querySelector(`#display-${name.replace('[]', '')}`);
                    if (displayElement) {
                        displayElement.innerHTML = eventInput.value;
                    }
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

                });

            }, target);
    }

    const openPengalaman = (callback, pengalaman) => {
        const modalElement = document.getElementById('modal-pengalaman');
        const btnTrue = modalElement.querySelector("#btn-true-pengalaman");
        const modalBody = modalElement.querySelector(".modal-body");
        modalBody.innerHTML = `@include('mahasiswa.profile.edit-pengalaman-modal-form')`;
        if (typeof pengalaman !== 'undefined') {
            const inputs = modalBody.querySelectorAll('input, textarea, select');
            inputs.forEach((input) => {
                const name = input.getAttribute('name');

                if (name === 'tipe_pengalaman[]') {
                    const target = pengalaman.querySelector(`input[name="${name}"]`);
                    document.querySelectorAll('input[name="tipe_pengalaman[]"]').forEach(el => {
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


        const tipe_pengalaman = document.querySelectorAll('input[name="tipe_pengalaman[]"]');
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
            target: document.querySelector('input[name="tipe_pengalaman[]"]:checked')
        });
        tipe_pengalaman.forEach(el => {
            el.addEventListener('change', switchMoreInformation);
        });

        const shownHandler = (event) => {
            btnTrue.onclick = () => {
                if (typeof callback === 'function')
                    callback({
                        target: modalBody.cloneNode(true)
                    });
                modalBody.innerHTML = "";
            };

        };

        const hiddenHandler = () => {
            tipe_pengalaman.forEach(el => {
                el.removeEventListener('change', switchMoreInformation);
            });
            modalElement.removeEventListener('shown.coreui.modal', shownHandler);
            modalElement.removeEventListener('hidden.coreui.modal', hiddenHandler);
        };

        modalElement.addEventListener('shown.coreui.modal', shownHandler);
        modalElement.addEventListener('hidden.coreui.modal', hiddenHandler);




        const modal = new coreui.Modal(modalElement);
        modal.show();
    };
</script>
