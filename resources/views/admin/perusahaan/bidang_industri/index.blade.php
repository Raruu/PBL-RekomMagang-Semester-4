@extends('layouts.app')
@section('title', 'Bidang Industri')
@section('content')
    <div class="d-flex flex-row gap-4 pb-4 position-relative container-fluid">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="d-flex flex-row justify-content-between flex-wrap card px-3 py-4">
                <h4 class="fw-bold mb-0">Bidang Industri</h4>
                <div class="d-flex flex-row gap-2">
                    <button type="button" class="btn btn-primary btn_add">
                        <i class="fas fa-plus"></i> Tambah Bidang Industri
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="bidangTable">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Bidang</th>
                                <th class="text-center">Jumlah Perusahaan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-page-modal title="Detail Bidang Industri" />
    <x-modal-yes-no dismiss="false" static="true">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Simpan
            </x-btn-submit-spinner>
        </x-slot>
    </x-modal-yes-no>
    <x-location-picker />

    <script>
        const run = () => {
            const table = $('#bidangTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.bidang_industri.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '50px'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'perusahaan',
                        name: 'perusahaan',
                        orderable: true,
                        searchable: false,
                        width: '32px',
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        width: '140px',
                        render: function(data, type, row) {
                            const bidang_id = row.bidang_id;
                            return `@include('admin.perusahaan.bidang_industri.aksi')`.replaceAll(':id', bidang_id);
                        }
                    },
                ],
            });

            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data admin ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(this.action)
                            .then(response => {
                                Swal.fire('Berhasil!', response.data.message, 'success');
                                table.ajax.reload();
                            })
                            .catch(error => {
                                Swal.fire(`Gagal ${error.status}`,
                                    error.response.data.message,
                                    'error');
                            });
                    }
                });
            });

            const addEditHandler = (modalElement, modal) => {
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                btnTrue.onclick = () => {
                    btnSpinerFuncs.spinBtnSubmit(modalElement);
                    const form = modalElement.querySelector('form');
                    axios.post(form.action, new FormData(form))
                        .then(response => {
                            modal.hide();
                            table.ajax.reload();
                            Swal.fire('Berhasil', response.data.message,
                                'success');
                            btnSpinerFuncs.resetBtnSubmit(modalElement);
                        })
                        .catch(error => {
                            console.error('Error updating data:', error);
                            Swal.fire('Error', error.response.data.message, 'error');
                            btnSpinerFuncs.resetBtnSubmit(modalElement);
                        });
                };
                btnFalse.onclick = () => {
                    modal.hide();
                };
            };

            $(document).on('click', '.btn_add', function() {
                const modalElement = document.querySelector('#modal-yes-no');
                const modal = new coreui.Modal(modalElement);
                axios.get('{{ route('admin.bidang_industri.create') }}')
                    .then(response => {
                        modalElement.querySelector('.modal-title').innerHTML = 'Tambah Bidang Industri';
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        addEditHandler(modalElement, modal);
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                    });
            });

            $(document).on('click', '.btn_edit', function() {
                const bidang_id = $(this).data('bidang_id');
                const modalElement = document.querySelector('#modal-yes-no');
                const modal = new coreui.Modal(modalElement);
                axios.get('{{ route('admin.bidang_industri.edit', ['id' => ':id']) }}'.replace(':id',
                        bidang_id))
                    .then(response => {
                        modalElement.querySelector('.modal-title').innerHTML = 'Tambah Bidang Industri';
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        addEditHandler(modalElement, modal);
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                    });
            });

            $(document).on('click', '.btn_show', async function() {
                const bidang_id = $(this).data('bidang_id');
                const modalShowElement = document.querySelector('#page-modal');
                const modal = new coreui.Modal(modalShowElement);
                axios.get('{{ route('admin.bidang_industri.show', ['id' => ':id']) }}'.replace(':id',
                        bidang_id))
                    .then(response => {
                        const body = modalShowElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                    });
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
