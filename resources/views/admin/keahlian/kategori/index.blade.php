@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
<div class="d-flex flex-row gap-4 pb-4 position-relative container-fluid">
    <div class="d-flex flex-column text-start gap-3 w-100">
        <div class="d-flex flex-row justify-content-between flex-wrap card px-3 py-4">
            <h4 class="fw-bold mb-0">Kategori</h4>
            <div class="d-flex flex-row gap-2">
                <button type="button" class="btn btn-primary btn_add">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="kategori_keahlianTable">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Kategori</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<x-page-modal title="Detail Kategori" />
<x-modal-yes-no dismiss="false" static="true">
    <x-slot name="btnTrue">
        <x-btn-submit-spinner size="22" wrapWithButton="false">
            Simpan
        </x-btn-submit-spinner>
    </x-slot>
</x-modal-yes-no>

<script>
    const run = () => {
        const table = $('#kategori_keahlianTable').DataTable({
            language: languageID,
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.keahlian.kategori.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '50px'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    width: '140px',
                    render: function(data, type, row) {
                        const kategori_id = row.kategori_id;
                        return `@include('admin.keahlian.kategori.aksi')`.replaceAll(':id', kategori_id);
                    }
                },
            ],
        });

        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kategori ini akan dihapus permanen!",
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
                            Swal.fire(`Gagal!`, error.response.data.message, 'error');
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
                const data = new FormData(form);
                for (const pair of data.entries()) {
                    data.set(pair[0], sanitizeString(pair[1]));
                }
                axios.post(form.action, data)
                    .then(response => {
                        modal.hide();
                        table.ajax.reload();
                        Swal.fire('Berhasil', response.data.message, 'success');
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
            axios.get('{{ route('admin.keahlian.kategori.create') }}')
                .then(response => {
                    modalElement.querySelector('.modal-title').innerHTML = 'Tambah Kategori';
                    const body = modalElement.querySelector(".modal-body");
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
            const kategori_id = $(this).data('kategori_id');
            const modalElement = document.querySelector('#modal-yes-no');
            const modal = new coreui.Modal(modalElement);
            axios.get('{{ route('admin.keahlian.kategori.edit', ['id' => ':id']) }}'.replace(':id', kategori_id))
                .then(response => {
                    modalElement.querySelector('.modal-title').innerHTML = 'Edit Kategori';
                    const body = modalElement.querySelector(".modal-body");
                    body.innerHTML = response.data;
                    addEditHandler(modalElement, modal);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    Swal.fire(`Error!`, 'Lihat console', 'error');
                });
        });

        $(document).on('click', '.btn_show', async function () {
            const kategori_id = $(this).data('kategori_id');
            const modalShowElement = document.querySelector('#page-modal'); // sesuaikan dengan ID modal kamu
            const modal = new coreui.Modal(modalShowElement);

            axios.get('{{ route('admin.keahlian.kategori.show', ['id' => ':id']) }}'.replace(':id', kategori_id))
                .then(response => {
                    const body = modalShowElement.querySelector(".modal-body");
                    body.innerHTML = '';
                    body.innerHTML = response.data;
                    modalShowElement.querySelector('.modal-title').innerHTML = 'Detail Kategori';
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
