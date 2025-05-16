@extends('layouts.app')
@section('title', 'Kegiatan Magang Mahasiswa')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4 position-relative">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <h4 class="fw-bold mb-0">Kegiatan Magang</h4>
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
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'lowongan',
                        name: 'lowongan',
                        searchable: true
                    },
                    {
                        data: 'mahasiswa',
                        name: 'mahasiswa',
                        searchable: true
                    },
                    {
                        data: 'dosen',
                        name: 'dosen',
                        searchable: true
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center align-middle',
                        orderable: false,
                        render: (data) => {
                            return `<span class="badge bg-${data == 'disetujui' ? 'success' : (data == 'ditolak' ? 'danger' : (data == 'menunggu' ? 'secondary' : 'info'))}">
                                ${data.charAt(0).toUpperCase() + data.slice(1)}
                                </span>`;
                        }
                    },
                ],
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
