@extends('layouts.app')
@section('title', 'Evaluasi SPK')
@push('start')
    @vite(['resources/js/admin/spk/index.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="card flex-row w-100">
            <div style="width: 400px;"><canvas id="bobot-chart"></canvas></div>
            <div class="flex-column d-flex w-100">
                <div class="d-flex flex-column gap-1 w-100 p-3 bobot_input">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h5 class="fw-bold">Bobot Parameter</h5>
                        <a href="{{ route('admin.evaluasi.spk.detail') }}"
                            class="btn btn-primary mx-2 d-flex flex-row gap-2 align-items-center">
                            <i class="fas fa-external-link-alt"></i> Edit
                        </a>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_ipk" class="input-group-text" style="min-width: 112px;">IPK</label>
                        <input type="number" class="form-control w-100" id="bobot_ipk" name="bobot_ipk"
                            value="{{ $spk['IPK'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_skill" class="input-group-text" style="min-width: 112px;">Skill</label>
                        <input type="number" class="form-control w-100" id="bobot_skill" name="bobot_skill"
                            value="{{ $spk['keahlian'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_pengalaman" class="input-group-text" style="min-width: 112px;">Pengalaman</label>
                        <input type="number" class="form-control w-100" id="bobot_pengalaman" name="bobot_pengalaman"
                            value="{{ $spk['pengalaman'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_jarak" class="input-group-text" style="min-width: 112px;">Jarak</label>
                        <input type="number" class="form-control w-100" id="bobot_jarak" name="bobot_jarak"
                            value="{{ $spk['jarak'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_posisi" class="input-group-text" style="min-width: 112px;">Posisi</label>
                        <input type="number" class="form-control w-100" id="bobot_posisi" name="bobot_posisi"
                            value="{{ $spk['posisi'] }}" step="0.0001" readonly disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column gap-2 mt-4">
            <div class="d-flex flex-row gap-2 w-100 justify-content-between align-content-center card px-3 py-4">
                <h5 class="fw-bold my-auto">Feedback dari Mahasiswa</h5>

                <div class="d-flex flex-row gap-2">
                    <a class="btn btn-outline-success export_excel" href="{{ route('admin.evaluasi.spk.feedback.excel') }}"
                        target="_blank">
                        <i class="fas fa-file-excel"></i>
                    </a>
                    <div class="input-group" style="max-width: 144px;">
                        <label class="input-group-text d-none d-md-block" for="show-limit">Show</label>
                        <select class="form-select" id="show-limit">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="input-group-text">Angkatan</label>
                        <select class="form-select filter_angkatan">
                            <option value="">Semua</option>
                            @for ($i = date('Y'); $i >= 2015; $i--)
                                <option value="{{ $i }}">
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <input type="text" class="form-control w-100" placeholder="Cari" name="search" id="search"
                        value="">
                </div>
            </div>
            <div class="card flex-row w-100 p-3">
                <div class="flex-column d-flex w-100">
                    <table id="feedbackTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Angkatan</th>
                                <th>Mahasiswa</th>
                                <th>Rating</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody style="cursor: pointer"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-page-modal title="Feedback Mahasiswa" id="modal-show" class="modal-lg">
    </x-page-modal>
    <script>
        const run = () => {
            const table = $('#feedbackTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.evaluasi.spk') }}',
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '10px'
                    },
                    {
                        data: 'angkatan',
                        name: 'angkatan',
                        width: '10px'
                    },
                    {
                        data: 'mahasiswa',
                        name: 'mahasiswa',
                        width: '30%'
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                        width: '10px'
                    },
                    {
                        data: 'feedback',
                        name: 'feedback',
                        width: '70%'
                    },
                ],
            });
            table.on('click', 'tr', function() {
                const data = table.row(this).data();
                console.log(data);
                axios.get(
                        '{{ route('admin.evaluasi.spk.feedback.show', ['feedback_spk_id' => ':feedback_spk_id']) }}'
                        .replace(':feedback_spk_id', data.feedback_spk_id))
                    .then(response => {
                        const modalElement = document.querySelector('#modal-show');
                        modalElement.querySelector('.modal-body').innerHTML = response.data;
                        const modal = new coreui.Modal(modalElement);
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire('Gagal!', 'Lihat console', 'error');
                    });

            });

            $('#feedbackTable_wrapper').children().first().addClass('d-none');

            const filterStatus = document.querySelector('.filter_angkatan');
            filterStatus.addEventListener('change', (event) => {
                table.column(1).search(event.target.value).draw();
            });
            const search = document.querySelector('#search');
            search.addEventListener('input', (event) => {
                table.search(event.target.value).draw();
            });
            const showLimit = document.querySelector('#show-limit');
            showLimit.addEventListener('change', (event) => {
                table.page.len(event.target.value).draw();
            });

            setTimeout(() => {
                table.column(1).search(filterStatus.value).draw();
                table.search(search.value).draw();
                table.page.len(showLimit.value).draw();
            }, 1);

        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
