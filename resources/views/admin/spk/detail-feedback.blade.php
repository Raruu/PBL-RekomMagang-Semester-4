@extends('layouts.app')
@section('title', 'Detail Feedback SPK')
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="d-flex flex-column gap-2 mt-4">
            <div class="d-flex flex-row gap-2 w-100 justify-content-between align-items-start card px-3 py-4">
                <div type="button" onclick="window.location.href='{{ route('admin.evaluasi.spk') }}'"
                    class="d-flex flex-row gap-2 align-items-center">
                    <i class="fas fa-arrow-left"></i>
                    <h5 class="fw-bold my-auto">Feedback dari Mahasiswa</h5>
                </div>

                <div class="d-flex flex-column gap-2 align-items-end">
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
                    <div class="d-flex flex-row gap-2">
                        <a class="btn btn-outline-success export_excel"
                            href="{{ route('admin.evaluasi.spk.feedback.excel') }}" target="_blank">
                            <i class="fas fa-file-excel"></i> Excel belum dibaca
                        </a>
                         <a class="btn btn-outline-warning export_excel"
                            href="{{ route('admin.evaluasi.spk.feedback.excel') }}?isRead=true" target="_blank">
                            <i class="fas fa-file-excel"></i> Excel Semua
                        </a>
                        <button type="button" class="btn btn-outline-danger btn_mark_as_read">
                            <i class="fas fa-eye"></i> Tandai sudah dibaca
                        </button>
                    </div>

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
                                <th>Komentar</th>
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
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.evaluasi.spk.feedback') }}',
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
                        width: '70%',
                        render: (data, type, row) => {
                            const tableElement = document.querySelector('#feedbackTable');
                            const tableWidth = tableElement.offsetWidth - 500;
                            return `<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: ${tableWidth}px;">${data}</div>`;
                        }
                    }
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

            const btnReadAll = document.querySelector('.btn_mark_as_read');
            btnReadAll.addEventListener('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda yakin ingin menandai semua feedback sebagai sudah dibaca?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tandai'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: '{{ route('admin.evaluasi.spk.feedback.markReadAll') }}',
                            type: 'PATCH',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: data.message,
                                    icon: 'success',
                                }).then(() => {
                                    table.ajax.reload();
                                });
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat menghubungi server',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
