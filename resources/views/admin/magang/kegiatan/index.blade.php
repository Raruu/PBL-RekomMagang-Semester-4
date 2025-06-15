@extends('layouts.app')
@section('title', 'Kegiatan Magang Mahasiswa')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-magang">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3"
                                style="width: 50px; height: 50px; background-color: var(--cui-primary-bg-subtle); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-tasks text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">Kegiatan Magang</h2>
                                <p class="text-body-secondary mb-0 opacity-75">Kelola semua kegiatan magang mahasiswa dengan mudah
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> kegiatan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row gap-2 mt-3 mb-3">
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
            <select class="form-select" id="filter-status"
                style="max-width: 200px; min-width: 130px; width: 200px">
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ Str::ucfirst($status) }}</option>
                @endforeach
                <option value="">Semua</option>
            </select>
            <input type="text" class="form-control w-100" placeholder="Cari" name="search" id="search"
                value="">
        </div>
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="card">
                <div class="card-body">
                    <table id="magangTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Lowongan</th>
                                <th>Mahasiswa</th>
                                <th>Dosen</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="cursor: pointer"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const run = () => {
            const table = $('#magangTable').DataTable({
                language: languageID,
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: '{{ route('admin.magang.kegiatan') }}',
                    type: 'GET',
                },
                order: [
                    [4, 'desc']
                ],
                columns: [{
                        width: '1%',
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lowongan',
                        name: 'lowongan',
                        searchable: true,
                        className: 'align-middle'
                    },
                    {
                        width: '25%',
                        data: 'mahasiswa',
                        name: 'mahasiswa',
                        searchable: true,
                        className: 'align-middle'
                    },
                    {
                        width: '30%',
                        data: 'dosen',
                        name: 'dosen',
                        searchable: true,
                        className: 'align-middle'
                    },
                    {
                        width: '190px',
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan',
                        searchable: true,
                        orderable: true,
                        className: 'align-middle',
                        render: (data) => {
                            return new Date(data).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                        }
                    },
                    {
                        width: '82px',
                        data: 'status',
                        name: 'status',
                        className: 'text-start align-middle',
                        orderable: false,
                        render: (data) => {
                            return `<span class="badge bg-${data == 'disetujui' ? 'success' : (data == 'ditolak' ? 'danger' : (data == 'menunggu' ? 'secondary' : 'info'))}">
                                ${data.charAt(0).toUpperCase() + data.slice(1)}
                                </span>`;
                        }
                    },
                ],
                initComplete: function(settings, json) {
                    if (json && json.recordsTotal !== undefined) {
                        document.getElementById('record-count').textContent = json.recordsTotal;
                    }
                }
            });
            table.on('click', 'tr', function() {
                const data = table.row(this).data();
                if (data && data.pengajuan_id) {
                    window.location.href =
                        `{{ route('admin.magang.kegiatan.detail', ['pengajuan_id' => ':id']) }}`
                        .replace(':id', data.pengajuan_id);
                }
            });

            $('#magangTable_wrapper').children().first().addClass('d-none');

            const filterStatus = document.querySelector('#filter-status');
            filterStatus.addEventListener('change', (event) => {
                table.column(5).search(event.target.value).draw();
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
                table.column(5).search(filterStatus.value).draw();
                table.search(search.value).draw();
                table.page.len(showLimit.value).draw();
            }, 1);
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
