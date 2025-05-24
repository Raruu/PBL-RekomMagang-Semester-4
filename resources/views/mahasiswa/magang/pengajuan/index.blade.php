@extends('layouts.app')
@section('title', 'Pengajuan Magang Mahasiswa')
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
                            <label class="input-group-text" for="status-pengajuan">Status</label>
                            <select class="form-select" id="status-pengajuan" name="status-pengajuan">
                                <option value="semua">Semua</option>
                                @foreach ($status as $value)
                                    <option value="{{ $value }}">
                                        {{ Str::ucfirst($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-text" for="tipe-lowongan">Tipe</label>
                            <select class="form-select" id="tipe-lowongan" name="tipe-lowongan">
                                <option value="semua">Semua</option>
                                @foreach ($tipeKerja as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="input-group-text" for="sort-by">Sort</label>
                            <select class="form-select" id="sort-by" name="sort-by">
                                <option value="0-asc">Judul (A-Z)</option>
                                <option value="0-desc">Judul (Z-A)</option>
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
                    <div class="d-flex flex-column gap-2 align-items-center justify-content-center">
                        <h5 class=" mb-0">Total Pengajuan</h5>
                        <h4 class="fw-bold mb-0">{{ $metrik['total'] }}</h4>
                    </div>

                    <div class="d-flex flex-row gap-2 align-items-center justify-content-center">
                        @foreach ($metrik as $key => $value)
                            @if ($key != 'total')
                                <div class="d-flex flex-column justify-content-center align-items-center" style="width: 80px">
                                    <h6 class="fw-bold">{{ $value }}</h6>
                                    <p class="mb-0">{{ Str::ucfirst($key) }}</p>
                                </div>
                                @if (!$loop->last)
                                    <div class="vr"></div>
                                @endif
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <div class="d-flex flex-column text-start gap-3 w-100">
            <h4 class="fw-bold mb-0">Pengajuan Magang</h4>
            <div class="card">
                <div class="card-body">
                    <table id="pengajuanTable" class="table table-striped table-hover d-none">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Deskripsi</th>
                                <th>Keahlian Lowongan</th>
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
            const table = $('#pengajuanTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax: {
                    url: '{{ route('mahasiswa.magang.pengajuan') }}',
                    type: 'GET',
                },
                order: [
                    [0, 'asc']
                ],
                columns: [{
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
                        data: 'keahlian_lowongan',
                        name: 'keahlian_lowongan',
                        searchable: true,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true,
                    }
                ],
                drawCallback: function() {
                    const api = this.api();
                    const rows = api.rows({
                        page: 'current'
                    }).data();
                    $('#pengajuanTable').parent().parent().before(
                        '<div class="mt-2" id="card-container"></div>');
                    const $grid = $('#card-container').empty();

                    rows.each(function(row) {
                        const card = `@include('mahasiswa.magang.pengajuan.pengajuan-datatable-card')`;
                        $grid.append(card);
                        const $card = $grid.find('.card-body').last();
                        $card.on('click', function() {
                            window.location.href =
                                `{{ route('mahasiswa.magang.pengajuan.detail', ['pengajuan_id' => ':id']) }}`
                                .replace(':id', row.pengajuan_id);
                        });
                    });
                },
            });
            $('#pengajuanTable_wrapper').children().first().addClass('d-none');
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
                const searchValue = table.column(3).search();
                if (searchValue.includes(value)) {
                    return;
                }
                table.column(3).search(searchValue + ' ' + value).draw();
            });
            tagify.on('remove', function(e) {
                const value = e.detail.data.value;
                const searchValue = table.column(3).search();
                if (!searchValue.includes(value)) {
                    return;
                }
                const newSearchValue = searchValue.replace(value, '').trim();
                table.column(3).search(newSearchValue).draw();
            });

            const search = cardControl.querySelector('#search');
            search.addEventListener('input', (event) => {
                table.search(event.target.value).draw();
            });
            const showLimit = cardControl.querySelector('#show-limit');
            showLimit.addEventListener('change', (event) => {
                table.page.len(event.target.value).draw();
            });
            const statusPengajuan = cardControl.querySelector('#status-pengajuan');
            statusPengajuan.addEventListener('change', (event) => {
                table.column(4).search(event.target.value == 'semua' ? '' : event.target.value).draw();
            });
            const tipeLowongan = cardControl.querySelector('#tipe-lowongan');
            tipeLowongan.addEventListener('change', (event) => {
                table.column(1).search(event.target.value == 'semua' ? '' : event.target.value).draw();
            });
            const sortBy = cardControl.querySelector('#sort-by');
            sortBy.addEventListener('change', (event) => {
                const [column, order] = event.target.value.split('-');
                table.order([parseInt(column), order]).draw();
            });

            setTimeout(() => {
                table.search(search.value).draw();
                table.page.len(showLimit.value).draw();
                table.column(4).search(statusPengajuan.value == 'semua' ? '' : statusPengajuan.value)
                    .draw();
                table.column(1).search(tipeLowongan.value == 'semua' ? '' : tipeLowongan.value)
                    .draw();
                const [column, order] = sortBy.value.split('-');
                table.order([parseInt(column), order]).draw();
            }, 1);
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
