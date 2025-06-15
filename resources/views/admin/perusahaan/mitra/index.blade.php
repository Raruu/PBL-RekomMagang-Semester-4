@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-mitra">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background-color: var(--cui-primary-bg-subtle);
                                border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $page->title }}</h2>
                                <p class="text-body-secondary mb-0 opacity-75">Kelola semua perusahaan mitra dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> perusahaan
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
                    <span>Tambah Perusahaan</span>
                </button>
            </div>
        </div>

        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="perusahaanTable" style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Bidang Industri</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 180px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-page-modal title="Detail Perusahaan" class="modal-lg" />
    <x-modal-yes-no dismiss="false" static="true" class="modal-lg">
        <x-slot name="btnTrue">
            <x-btn-submit-spinner size="22" wrapWithButton="false">
                Simpan
            </x-btn-submit-spinner>
        </x-slot>
    </x-modal-yes-no>
    <x-location-picker />
@endsection

@push('end')
    <script>
        const run = () => {
            const table = $('#perusahaanTable').DataTable({
                language: languageID,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.perusahaan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_perusahaan',
                        name: 'nama_perusahaan'
                    },
                    {
                        data: 'bidang_industri',
                        name: 'bidang_industri'
                    },
                    {
                        data: 'lokasi.alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        width: '180px',
                        render: function(data, type, row) {
                            const isActive = data.status;
                            const perusahaan_id = data.perusahaan_id;
                            const nama_perusahaan = data.nama_perusahaan;
                            return `@include('admin.perusahaan.mitra.aksi')`.replaceAll(':id', perusahaan_id);
                        }
                    },
                ],

                // Update record-count badge after table load
                initComplete: function(settings, json) {
                    if (json && json.recordsTotal !== undefined) {
                        document.getElementById('record-count').textContent = json.recordsTotal;
                    }
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

            $(document).on('click', '.btn_show', async function() {
                const perusahaan_id = $(this).data('perusahaan_id');
                const modalShowElement = document.querySelector('#page-modal');
                const modal = new coreui.Modal(modalShowElement);
                axios.get('{{ route('admin.perusahaan.show', ['id' => ':id']) }}'.replace(':id',
                        perusahaan_id))
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

            const editOrAddHandler = (modalElement, modal) => {
                const btnFalse = modalElement.querySelector('#btn-false-yes-no');
                const btnTrue = modalElement.querySelector('#btn-true-yes-no');
                btnTrue.onclick = () => {
                    btnSpinerFuncs.spinBtnSubmit(modalElement);
                    const form = modalElement.querySelector('form');
                    form.querySelectorAll('.is-invalid').forEach(input => {
                        input.classList.remove('is-invalid');
                        $('#error-' + input.name).text('');
                    });
                    const data = new FormData(form);
                    for (const pair of data.entries()) {
                        data.set(pair[0], sanitizeString(pair[1]));
                    }
                    axios.post(form.action, data)
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
                            console.log(error.response.data.msgField);
                            $.each(error.response.data.msgField,
                                function(prefix, val) {
                                    $(`[name="${prefix}"]`).addClass('is-invalid');
                                    $('#error-' + prefix).text(val[0]);
                                });

                        });
                };
                btnFalse.onclick = () => {
                    modal.hide();
                };
                const preferensiPickLocation = () => {
                    const longitude = modalElement.querySelector('#location_longitude');
                    const latitude = modalElement.querySelector('#location_latitude');

                    openLocationPicker((event) => {
                        modalElement.querySelector('#lokasi_alamat').value = event
                            .locationOutput.address;
                        latitude.value = event.locationOutput.lat;
                        longitude.value = event.locationOutput.lng;
                    }, modalElement.querySelector('#lokasi_alamat').value, {
                        lat: latitude.value == '' ? -6.21462 : latitude.value,
                        lng: longitude.value == '' ? 106.84513 : longitude.value
                    })
                };
                modalElement.querySelector('.btn_pick_location').onclick = preferensiPickLocation;
            };

            $(document).on('click', '.btn_edit', async function() {
                const perusahaan_id = $(this).data('perusahaan_id');
                const modalElement = document.querySelector('#modal-yes-no');
                const modal = new coreui.Modal(modalElement);

                axios.get('{{ route('admin.perusahaan.edit', ['id' => ':id']) }}'.replace(':id',
                        perusahaan_id))
                    .then(response => {
                        modalElement.querySelector('.modal-title').innerHTML = 'Edit Perusahaan';
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        editOrAddHandler(modalElement, modal);
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        Swal.fire(`Error!`, 'Lihat console', 'error');
                    });
            });

            $(document).on('click', '.toggle-status-btn', function() {
                const perusahaan_id = $(this).data('perusahaan_id');
                const nama_perusahaan = $(this).data('nama_perusahaan');

                Swal.fire({
                    title: 'Ubah Status?',
                    text: `Anda yakin ingin mengubah status ${nama_perusahaan}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: "{{ route('admin.perusahaan.toggle-status', ['id' => ':id']) }}"
                                .replace(':id', perusahaan_id),
                            method: 'PATCH',
                            success: function(res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: true
                                });

                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                console.error('Error response:', xhr);
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.error ||
                                        'Terjadi kesalahan.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn_add', function() {
                const modalElement = document.querySelector('#modal-yes-no');
                const modal = new coreui.Modal(modalElement);

                axios.get('{{ route('admin.perusahaan.create') }}')
                    .then(response => {
                        modalElement.querySelector('.modal-title').innerHTML = 'Tambah Perusahaan';
                        const body = modalElement.querySelector(".modal-body");
                        body.innerHTML = '';
                        body.innerHTML = response.data;
                        editOrAddHandler(modalElement, modal);
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
@endpush
