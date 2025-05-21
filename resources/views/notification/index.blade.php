@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
    <style>
        #card-container .card-body {
            transition: background-color 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #card-container .card-body:hover {
            background-color: var(--cui-secondary-bg);
        }

        .input-group .input-group-text {
            width: 65px;
        }
    </style>
    <div class="d-flex flex-row gap-4 pb-4 position-relative">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="fw-bold mb-0">Notifikasi {{ Str::ucfirst(Auth::user()->role) }}</h4>
                <button type="button" class="btn btn-success" onclick="notificationMarkReadAll()">
                    <i class="fas fa-check-double"></i>
                    Tandai semua sudah dibaca
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h6>Notifikasi</h6>
                        <div class="d-flex flex-row gap-2 w-50" id="card-control">
                            <div class="input-group" style="max-width: 144px;">
                                <label class="input-group-text" for="show-limit">Show</label>
                                <select class="form-select" id="show-limit">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                            <select class="form-select w-50" id="filter-read" style="max-width: 200px; min-width: 130px">
                                <option value="1">Belum Dibaca</option>
                                <option value="0">Sudah Dibaca</option>
                                <option value="">Semua</option>
                            </select>
                            <input type="text" class="form-control w-100" placeholder="Cari" name="search"
                                id="search" value="">
                        </div>
                    </div>
                    <table id="notifikasiTable" class="table d-none">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>linkTitle</th>
                                <th>read</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const notificationMarkRead = (id, link) => {
            notifications.markRead(id, link).then(response => {
                if (link !== '') {
                    window.location.href = link;
                } else {
                    $('#notifikasiTable').DataTable().ajax.reload(null, false);
                }
            });

        };

        const notificationMarkReadAll = () => {
            notifications.markReadAll().then(response => {
                if (response.success) {
                    $('#notifikasiTable').DataTable().ajax.reload(null, false);
                } else {
                    console.log(response.message);
                }
            });
        };

        const run = () => {
            const table = $('#notifikasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route(Auth::user()->role . '.notifikasi') }}',
                    type: 'GET',
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                    },
                    {
                        data: 'pesan',
                        name: 'pesan',
                        searchable: true,
                    },
                    {
                        data: 'linkTitle',
                        name: 'linkTitle'
                    },
                    {
                        data: 'read',
                        name: 'read',
                    },
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'link',
                        name: 'link',
                    }
                ],
                drawCallback: function() {
                    const api = this.api();
                    const rows = api.rows({
                        page: 'current'
                    }).data();
                    $('#notifikasiTable').parent().parent().before(
                        '<div class="mt-2" id="card-container"></div>');
                    const $grid = $('#card-container').empty();

                    rows.each(function(row) {
                        let card = `@include('notification.notification-card')`;

                        if (row.read == "1") {
                            card = card.replace('#showmarkread',
                                `<button class="btn btn-outline-success btn-sm" type="button"
                                    onclick="notificationMarkRead('${row.id}', '')">
                                    <i class="fa-regular fa-eye"></i>
                                </button>`);
                        } else {
                            card = card.replace('#showmarkread', '');
                        }
                        $grid.append(card);
                    });
                },
            });
            $('#notifikasiTable_wrapper').children().first().addClass('d-none');

            const cardControl = document.getElementById('card-control');
            cardControl.querySelector('#show-limit').addEventListener('change', (event) => {
                table.page.len(event.target.value).draw();
            });
            setTimeout(() => {
                const fileterRead = cardControl.querySelector('#filter-read');
                table.column(3).search(fileterRead.value).draw();
            }, 1);

            cardControl.querySelector('#filter-read').addEventListener('change', (event) => {
                table.column(3).search(event.target.value).draw();
            });
            cardControl.querySelector('#search').addEventListener('input', (event) => {
                table.search(event.target.value).draw();
            })
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
