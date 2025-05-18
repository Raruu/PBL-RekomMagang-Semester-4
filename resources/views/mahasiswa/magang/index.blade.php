@extends('layouts.app')
@section('title', 'Magang Mahasiswa')
@section('content')
    @vite(['resources/js/import/tagify.js'])
    <style>
        #card-container .card-body {
            transition: background-color 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #card-container .card-body:hover {
            background-color: var(--cui-secondary-bg);
            cursor: pointer;
        }

        .input-group .input-group-text {
            width: 65px;
        }
    </style>
    <div class="d-flex flex-row gap-4 pb-4 position-relative">
        <div class="d-flex flex-column w-25 gap-3 user-select-none"
            style="min-width: 325px; max-width: 325px; pointer-events: none"></div>
        <div class="d-flex flex-column gap-3 position-fixed pb-5"
            style="width: 325px;max-width: 325px;top: 138px; z-index: 1036; max-height: calc(100vh - 118px); overflow-y: auto;">
            <div class="d-flex flex-column text-start gap-3">
                <h4 class="fw-bold mb-0">Filter</h4>
                <div class="card">
                    <div class="card-body d-flex flex-column gap-2" id="card-control">
                        <input type="text" class="form-control" placeholder="Cari" name="search" id="search"
                            value="">
                        <div class="input-group">
                            <label class="input-group-text" for="show-limit">Show</label>
                            <select class="form-select" id="show-limit" name="show-limit">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-text" for="tipe-lowongan">Tipe</label>
                            <select class="form-select" id="tipe-lowongan" name="tipe-lowongan">
                                <option value="">Semua</option>
                                @foreach ($tipeKerja as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-text" for="sort-by">Sort</label>
                            <select class="form-select" id="sort-by" name="sort-by">
                                <option value="0-asc">Skor (Ascending)</option>
                                <option value="0-desc" selected>Skor (Descending)</option>
                                <option value="1-asc">Judul (A-Z)</option>
                                <option value="1-desc">Judul (Z-A)</option>
                                <option value="5-asc">Gaji (Ascending)</option>
                                <option value="5-desc">Gaji (Descending)</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-text" for="tag">Tag</label>
                            <input type="text" class="form-control" placeholder="Tag" name="tag" id="tag"
                                value="">
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-flex flex-column gap-2">
                    <p class="mb-0">Sistem Rekomendasi Berdasarkan Preferensi di Profil</p>
                    <p class="mb-0">Sesuaikan di profil Anda</p>
                    <a href="{{ route('mahasiswa.profile.edit') }}" class="btn btn-primary w-100 mt-2">
                        Edit Profil
                    </a>
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
                                <th>Tipe</th>
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
                pageLength: 5,
                ajax: {
                    url: '{{ route('mahasiswa.magang') }}',
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
                        data: 'tipe_kerja_lowongan',
                        name: 'tipe_kerja_lowongan',
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
                            window.location.href =
                                `{{ route('mahasiswa.magang.lowongan.detail', ['lowongan_id' => ':id']) }}?backable=true`
                                .replace(':id', row.lowongan_id);
                        });
                    });
                },
                initComplete: function(settings, json) {
                    const tipeLowongan = cardControl.querySelector('#tipe-lowongan');
                    const nav = performance.getEntriesByType("navigation")[0];
                    if (nav == null || nav.type !== "back_forward") {
                        tipeLowongan.value =
                            '{{ $mahasiswa->preferensiMahasiswa->tipe_kerja_preferensi }}';
                    }
                    table.column(2).search(tipeLowongan.value == '' ? '' : tipeLowongan.value)
                        .draw();
                },
            });
            $('#magangTable_wrapper').children().first().addClass('d-none');
            const cardControl = document.getElementById('card-control');

            const tagify = new Tagify(cardControl.querySelector('#tag'), {
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

            tagify.on('add', function(e) {
                const value = e.detail.data.value;
                const searchValue = table.column(6).search();
                if (searchValue.includes(value)) {
                    return;
                }
                table.column(6).search(searchValue + ' ' + value).draw();
            });
            tagify.on('remove', function(e) {
                const value = e.detail.data.value;
                const searchValue = table.column(6).search();
                if (!searchValue.includes(value)) {
                    return;
                }
                const newSearchValue = searchValue.replace(value, '').trim();
                table.column(6).search(newSearchValue).draw();
            });

            cardControl.querySelector('#search').addEventListener('input', (event) => {
                table.search(event.target.value).draw();
            });
            cardControl.querySelector('#show-limit').addEventListener('change', (event) => {
                table.page.len(event.target.value).draw();
            });
            const tipeLowongan = cardControl.querySelector('#tipe-lowongan');
            tipeLowongan.addEventListener('change', (event) => {
                table.column(2).search(event.target.value == '' ? '' : event.target.value).draw();
            });
            cardControl.querySelector('#sort-by').addEventListener('change', (event) => {
                const [column, order] = event.target.value.split('-');
                table.order([parseInt(column), order]).draw();
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
