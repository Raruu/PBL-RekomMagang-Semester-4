@extends('layouts.app')
@section('title', 'Kegiatan Magang Mahasiswa')
@section('content-top')
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
    <x-modal-yes-no id="modal-edit" dismiss="false" static="true" title="Kegiatan Magang">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Simpan
            </x-btn-submit-spinner>
        </x-slot>
        <form action="{{ route('admin.magang.kegiatan.post') }}" method="POST">
            @csrf
            <input type="hidden" id="pengajuan_id" name="pengajuan_id" value="">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" id="status" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ $status == 'menunggu' ? 'disabled' : '' }}>
                            {{ Str::ucfirst($status) }}</option>
                    @endforeach
                </select>
                <div id="error-status" class="text-danger"></div>
            </div>
            <div class="mb-3">
                <label for="dosen_id" class="form-label">Dosen</label>
                <select name="dosen_id" class="form-select" id="dosen_id" required>
                    <option value="" selected disabled>Pilih Dosen</option>
                    @foreach ($dosen as $item)
                        <option value="{{ $item->dosen_id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
                <div id="error-dosen_id" class="text-danger"></div>
            </div>
        </form>

    </x-modal-yes-no>
    <script>
        const run = () => {
            const modalEditElement = document.querySelector('#modal-edit');
            const modalEdit = new coreui.Modal(modalEditElement);
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
                        searchable: true
                    },
                    {
                        width: '25%',
                        data: 'mahasiswa',
                        name: 'mahasiswa',
                        searchable: true
                    },
                    {
                        width: '30%',
                        data: 'dosen',
                        name: 'dosen',
                        searchable: true
                    },
                    {
                        width: '190px',
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan',
                        searchable: true,
                        orderable: true
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
                modalEditElement.querySelector('#pengajuan_id').value = '';
                modalEditElement.querySelector('#status').value = '';
                modalEditElement.querySelector('#dosen_id').value = '';
                modalEditElement.querySelector('#status').value = data.status;
                modalEditElement.querySelector('#dosen_id').value = data.dosen_id;
                modalEditElement.querySelector('#pengajuan_id').value = data.pengajuan_id;
                modalEdit.show();
            });

            modalEditElement.querySelector('#btn-false-yes-no').onclick = () => {
                modalEdit.hide();
            };
            const btnEditTrue = modalEditElement.querySelector('#btn-true-yes-no');
            btnEditTrue.onclick = () => {
                btnEditTrue.disabled = true;
                const form = modalEditElement.querySelector('form');
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                modalEdit.hide();
                                table.ajax.reload();
                            });
                        } else {
                            console.log(data);
                            Swal.fire('Gagal!', data.message, 'error');
                            $.each(data.msgField, function(prefix, val) {
                                const field = form.querySelector(`[name="${prefix}"]`);
                                field.classList.add('is-invalid');
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', error.message, 'error');
                    }).then(() => {
                        btnEditTrue.disabled = false;                      
                    });
            };
            modalEditElement.addEventListener('hidden.coreui.modal', function(event) {
                const errorElements = modalEditElement.querySelectorAll('.is-invalid');
                errorElements.forEach(el => {
                    el.classList.remove('is-invalid');
                    const errorId = el.id.replace('error-', '');
                    document.querySelector(`#error-${errorId}`).innerHTML = '';
                });
            });

        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
