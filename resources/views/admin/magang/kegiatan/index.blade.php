@extends('layouts.app')
@section('title', 'Kegiatan Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4 position-relative container-fluid">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="d-flex flex-row justify-content-between flex-wrap">
                <h4 class="fw-bold mb-0">Kegiatan Magang</h4>
                <div class="d-flex flex-row gap-2">
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
                    <select class="form-select" id="filter-status" style="max-width: 200px; min-width: 130px; width: 200px">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ Str::ucfirst($status) }}</option>
                        @endforeach
                        <option value="">Semua</option>
                    </select>
                    <input type="text" class="form-control w-100" placeholder="Cari" name="search" id="search"
                        value="">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="magangTable" class="table table-striped table-hover">
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
                        className: 'align-middle'
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
                initComplete: function() {
                    $('#magangTable_wrapper').children().first().addClass('d-none');
                    table.column(5).search('menunggu').draw();
                    document.querySelector('#filter-status').addEventListener('change', (event) => {
                        table.column(5).search(event.target.value).draw();
                    });
                    document.querySelector('#search').addEventListener('input', (event) => {
                        table.search(event.target.value).draw();
                    });
                    document.querySelector('#show-limit').addEventListener('change', (event) => {
                        table.page.len(event.target.value).draw();
                    });
                }
            });
            table.on('click', 'tr', function() {
                const data = table.row(this).data();
                window.location.href = `{{ route('admin.magang.kegiatan.detail', ['pengajuan_id' => ':id']) }}`
                    .replace(':id', data.pengajuan_id);
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
