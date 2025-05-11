@extends('layouts.app')
@section('title', 'Magang Mahasiswa')
@section('content')
    <style>
        #card-container .card-body {
            transition: background-color 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #card-container .card-body:hover {
            background-color: var(--cui-secondary-bg);
            cursor: pointer;
        }
    </style>
    <div class="d-flex flex-row gap-4 pb-4 position-relative">
        <div class="d-flex flex-column text-start gap-3 w-25">
            <h4 class="fw-bold mb-0">Control</h4>
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>

        <div class="d-flex flex-column text-start gap-3 w-100">
            <h4 class="fw-bold mb-0">Lowongan Magang</h4>
            <div class="card">
                <div class="card-body">
                    <table id="magangTable" class="table table-striped table-hover d-none">
                        <thead>
                            <tr>
                                <th class="text-center">Skor</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Batas Pendaftaran</th>
                                <th>Gaji</th>
                                <th>Keahlian Lowongan</th>
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
                ajax: {
                    url: '{{ url('/mahasiswa/magang') }}',
                    type: 'GET',
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'skor',
                        name: 'skor',
                        orderable: true,
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: 'batas_pendaftaran',
                        name: 'batas_pendaftaran'
                    },
                    {
                        data: 'gaji',
                        name: 'gaji'
                    },
                    {
                        data: 'keahlian_lowongan',
                        name: 'keahlian_lowongan',
                        searchable: true,
                    },
                ],
                drawCallback: function() {
                    const api = this.api();
                    const rows = api.rows({
                        page: 'current'
                    }).data();
                    $('#magangTable').parent().parent().before(
                        '<div class="mt-2" id="card-container"></div>');

                    const $grid = $('#card-container').empty();

                    rows.each(function(row) {
                        const card = `@include('mahasiswa.magang.lowongan-datatable-card')`;
                        $grid.append(card);
                        const $card = $grid.find('.card-body').last();
                        $card.on('click', function() {
                            window.location.href = `/mahasiswa/magang/${row.lowongan_id}`;
                        });
                    });
                }
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
