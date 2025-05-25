@extends('layouts.app')
@section('title', 'Edit Bobot SPK')
@push('start')
    @vite(['resources/js/admin/spk/edit-bobot.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <h5 class="fw-bold">Edit Bobot SPK</h5>
        <div class="card flex-row w-100">
            <div style="width: 800px;"><canvas id="bobot-chart"></canvas></div>
            <form class="d-flex flex-column gap-1 w-100 p-3 bobot_input" method="POST"
                action="{{ route('admin.evaluasi.spk.update') }}">
                @csrf
                @method('PUT')
                <h5 class="fw-bold">Bobot Parameter</h5>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_ipk" class="input-group-text" style="min-width: 112px;">IPK</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_ipk" name="bobot_ipk"
                        min="0.0" max="1.0" value="{{ $spk['IPK'] }}" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_ipk_text"
                        value="{{ $spk['IPK'] }}" style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_skill" class="input-group-text" style="min-width: 112px;">Skill</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_skill"
                        name="bobot_skill" min="0.0" max="1.0" value="{{ $spk['keahlian'] }}" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_skill_text"
                        value="{{ $spk['keahlian'] }}" style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_pengalaman" class="input-group-text" style="min-width: 112px;">Pengalaman</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_pengalaman"
                        name="bobot_pengalaman" min="0.0" max="1.0" value="{{ $spk['pengalaman'] }}"
                        step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_pengalaman_text"
                        value="{{ $spk['pengalaman'] }}" style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_jarak" class="input-group-text" style="min-width: 112px;">Jarak</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_jarak"
                        name="bobot_jarak" min="0.0" max="1.0" value="{{ $spk['jarak'] }}" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_jarak_text"
                        value="{{ $spk['jarak'] }}" style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_posisi" class="input-group-text" style="min-width: 112px;">Posisi</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_posisi"
                        name="bobot_posisi" min="0.0" max="1.0" value="{{ $spk['posisi'] }}" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_posisi_text"
                        value="{{ $spk['posisi'] }}" style="max-width: 96px;">
                </div>
                <div class="d-flex flex-row gap-2 justify-content-between px-1">
                    <button type="button" class="btn btn-warning"
                        onclick="editBobot.normalize().then(() => loadAdjustedTable())">
                        <i class="fas fa-redo-alt"></i> Normalisasi
                    </button>
                    <div class="d-flex flex-row gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" onclick="resetBobot()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-submit">
                            <x-btn-submit-spinner wrapWithButton="false" size="22">
                                <i class="fas fa-save"></i> Simpan
                            </x-btn-submit-spinner>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <h5 class="fw-bold">Bandingkan</h5>
            <a href="{{ route('admin.evaluasi.spk.profile-testing') }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-external-link-alt"></i> Buka Profile Testing
            </a>
        </div>
        <div class="flex-row d-flex w-100 gap-2">
            <div class="card flex-column d-flex w-100">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h5 class="card-title my-auto">Sebelum</h5>
                    <button type="button" class="btn btn-outline-info btn-sm" data-coreui-toggle="modal"
                        data-coreui-target="#modal-show">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <table id="beforeTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody style="cursor: pointer"></tbody>
                    </table>
                </div>
            </div>
            <div class="card flex-column d-flex w-100">
                <div class="card-header d-flex flex-row justify-content-between" style="height: 48px;">
                    <h5 class="card-title my-auto">Sesudah
                        <span class="text-muted" style="font-size: 12px;">*geser parameter dan tekan "Normalisasi" untuk
                            melihat hasil</span>
                    </h5>
                </div>
                <div class="card-body">
                    <table id="afterTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody style="cursor: pointer"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-page-modal title="Skor Asli" id="modal-show">
        <div class="d-flex flex-column gap-2 pb-4">
            <h5 class="fw-bold">Bobot</h5>
            <ul class="list-group">
                <li class="list-group-item">IPK: {{ $spk['IPK'] }}</li>
                <li class="list-group-item">Skill: {{ $spk['keahlian'] }}</li>
                <li class="list-group-item">Pengalaman: {{ $spk['pengalaman'] }}</li>
                <li class="list-group-item">Jarak: {{ $spk['jarak'] }}</li>
                <li class="list-group-item">Posisi: {{ $spk['posisi'] }}</li>
            </ul>
        </div>
    </x-page-modal>

    <x-page-modal title="Info Lowongan" id="modal-lowongan" class="modal-xl">
    </x-page-modal>

    <script>
        const loadAdjustedTable = () => {
            $('#afterTable').DataTable().ajax.reload(null, false);
        };

        const resetBobot = () => {
            console.log('reset');
            const inputIpk = document.querySelector("#bobot_ipk");
            const inputSkill = document.querySelector("#bobot_skill");
            const inputPengalaman = document.querySelector("#bobot_pengalaman");
            const inputJarak = document.querySelector("#bobot_jarak");
            const inputPosisi = document.querySelector("#bobot_posisi");
            inputIpk.value = "{{ $spk['IPK'] }}";
            inputSkill.value = "{{ $spk['keahlian'] }}";
            inputPengalaman.value = "{{ $spk['pengalaman'] }}";
            inputJarak.value = "{{ $spk['jarak'] }}";
            inputPosisi.value = "{{ $spk['posisi'] }}";
            editBobot.normalize().then(() => loadAdjustedTable());
        };

        const run = () => {
            const btnSubmit = document.querySelector("#btn-submit");
            const form = document.querySelector(".bobot_input");
            btnSubmit.onclick = () => {
                btnSpinerFuncs.spinBtnSubmit(form);
                console.log(new FormData(form));
                $.ajax({
                    url: form.action,
                    type: "POST",
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
                        console.error('Error:', response.responseJSON);
                        Swal.fire({
                            title: `Gagal ${response.status}`,
                            text: response.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        $.each(response.responseJSON.msgField,
                            function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                    },
                    complete: function() {
                        btnSpinerFuncs.resetBtnSubmit(form);
                    }
                })
            };

            const columns = [{
                    width: '1%',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'judul',
                    name: 'judul',
                    searchable: true,
                    className: 'align-middle'
                }, {
                    data: 'skor',
                    name: 'skor',
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row, meta) {
                        return `<span class="badge  bg-${ row.skor > 0.7 ? 'success' : (row.skor > 0.5 ? 'warning' : 'danger') }">${(row.skor * 100).toFixed(2)}%</span>`;
                    }
                }
            ];

            const beforeTable = $('#beforeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.evaluasi.spk.detail') }}',
                    type: 'GET',
                    data: (d) => {
                        d.bobot_ipk = "{{ $spk['IPK'] }}";
                        d.bobot_skill = "{{ $spk['keahlian'] }}";
                        d.bobot_pengalaman = "{{ $spk['pengalaman'] }}";
                        d.bobot_jarak = "{{ $spk['jarak'] }}";
                        d.bobot_posisi = "{{ $spk['posisi'] }}";
                        return d;
                    }
                },
                columns: columns,
            });

            const inputIpk = document.querySelector("#bobot_ipk");
            const inputSkill = document.querySelector("#bobot_skill");
            const inputPengalaman = document.querySelector("#bobot_pengalaman");
            const inputJarak = document.querySelector("#bobot_jarak");
            const inputPosisi = document.querySelector("#bobot_posisi");
            const afterTable = $('#afterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.evaluasi.spk.detail') }}',
                    type: 'GET',
                    data: (d) => {
                        d.bobot_ipk = inputIpk.value;
                        d.bobot_skill = inputSkill.value;
                        d.bobot_pengalaman = inputPengalaman.value;
                        d.bobot_jarak = inputJarak.value;
                        d.bobot_posisi = inputPosisi.value;
                        return d;
                    }
                },
                columns: columns,
            });

            const modalLowoganElement = document.querySelector("#modal-lowongan");
            const modalLowongan = new coreui.Modal(modalLowoganElement);
            beforeTable.on('click', 'tr', function() {
                const data = beforeTable.row(this).data();
                axios.get('{{ route('admin.evaluasi.spk.lowongan') }}', {
                        params: {
                            lowongan_id: data.lowongan_id,
                            bobot_ipk: "{{ $spk['IPK'] }}",
                            bobot_skill: "{{ $spk['keahlian'] }}",
                            bobot_pengalaman: "{{ $spk['pengalaman'] }}",
                            bobot_jarak: "{{ $spk['jarak'] }}",
                            bobot_posisi: "{{ $spk['jarak'] }}"
                        }
                    })
                    .then(response => {
                        console.log(response.data);
                        modalLowoganElement.querySelector(".modal-body").innerHTML = response.data;
                        modalLowongan.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });
            afterTable.on('click', 'tr', function() {
                const data = afterTable.row(this).data();
                axios.get('{{ route('admin.evaluasi.spk.lowongan') }}', {
                        params: {
                            lowongan_id: data.lowongan_id,
                            bobot_ipk: inputIpk.value,
                            bobot_skill: inputSkill.value,
                            bobot_pengalaman: inputPengalaman.value,
                            bobot_jarak: inputJarak.value,
                            bobot_posisi: inputPosisi.value
                        }
                    })
                    .then(response => {
                        console.log(response.data);
                        modalLowoganElement.querySelector(".modal-body").innerHTML = response.data;
                        modalLowongan.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });
        };
        document.addEventListener("DOMContentLoaded", run);
    </script>
@endsection
