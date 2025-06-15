@extends('layouts.app')
@section('title', 'Bidang Industri')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-bidang">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background-color: var(--cui-primary-bg-subtle);
                                border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-industry text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">Bidang Industri</h2>
                                <p class="text-body-secondary mb-0 opacity-75">Kelola semua bidang industri perusahaan dengan mudah
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> bidang
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-primary btn-action d-flex align-items-center btn_add">
                    <i class="fas fa-plus me-2"></i>
                    <span>Tambah Bidang Industri</span>
                </button>
            </div>
        </div>
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="bidangTable"
                            style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Nama Bidang</th>
                                    <th class="text-center">Jumlah Perusahaan</th>
                                    <th class="text-center" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
                language: languageID,
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

            $('#bidangTable').on('xhr.dt', function(e, settings, json, xhr) {
                if (json && json.recordsTotal !== undefined) {
                    document.getElementById('record-count').textContent = json.recordsTotal;
                }
            });

            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        axios.delete(this.action)
                            .then(response => {
                                Swal.fire('Berhasil!', response.data.message, 'success');
                                table.ajax.reload();
                            })
                            .catch(error => {
                                Swal.fire(`Gagal!`,
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
                    const formData = new FormData(form);
                    formData.set('nama', sanitizeString(formData.get('nama')));
                    axios.post(form.action, formData)
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
                            if (error.response.data.msgField) {
                                $.each(error.response.data.msgField, function(prefix, val) {
                                    const errorDiv = document.createElement('div');
                                    errorDiv.id = `error-${prefix}`;
                                    errorDiv.className = 'text-danger';
                                    errorDiv.textContent = val[0];
                                    form.querySelector(`[name="${prefix}"]`).parentElement
                                        .appendChild(errorDiv);
                                });
                            }
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
                        Swal.fire(`Error!`, 'Lihat console', 'error');
                    });
            });

            $(document).on('click', '.btn_edit', function() {
                const bidang_id = $(this).data('bidang_id');
                const modalElement = document.querySelector('#modal-yes-no');
                const modal = new coreui.Modal(modalElement);
                axios.get('{{ route('admin.bidang_industri.edit', ['id' => ':id']) }}'.replace(':id',
                        bidang_id))
                    .then(response => {
                        modalElement.querySelector('.modal-title').innerHTML = 'Edit Bidang Industri';
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        addEditHandler(modalElement, modal);
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error!`, 'Lihat console', 'error');
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
                        Swal.fire(`Error!`, 'Lihat console', 'error');
                    });
            });
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
