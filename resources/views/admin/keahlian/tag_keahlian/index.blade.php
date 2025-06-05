@extends('layouts.app')
@section('title', 'Tag Keahlian')
@section('content')
<div class="d-flex flex-row gap-4 pb-4 position-relative container-fluid">
    <div class="d-flex flex-column text-start gap-3 w-100">
        <div class="d-flex flex-row justify-content-between flex-wrap card px-3 py-4">
            <h4 class="fw-bold mb-0">Tag Keahlian</h4>
            <div class="d-flex flex-row gap-2">
                <button type="button" class="btn btn-primary btn_add">
                    <i class="fas fa-plus"></i> Tambah Tag Keahlian
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="keahlianTable">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Keahlian</th>
                            <th class="text-center">ID Keahlian</th>
                            <th class="text-center">Nama Kategori</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<x-page-modal title="Detail Tag Keahlian" />
<x-modal-yes-no dismiss="false" static="true">
    <x-slot name="btnTrue">
        <x-btn-submit-spinner size="22" wrapWithButton="false">Simpan</x-btn-submit-spinner>
    </x-slot>
</x-modal-yes-no>

<script>
    const run = () => {
        const table = $('#keahlianTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.keahlian.tag_keahlian.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '50px',
                },
                {
                    data: 'nama_keahlian',
                    name: 'nama_keahlian'
                },
                {
                    data: 'keahlian_id',
                    name: 'keahlian_id'
                },
                {
                    data: 'kategori.nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    width: '140px',
                    render: function (data, type, row) {
                        const keahlian_id = row.keahlian_id;
                        return `@include('admin.keahlian.tag_keahlian.aksi')`.replaceAll(':id', keahlian_id);
                    }
                },
            ]
        });

        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data tag keahlian ini akan dihapus permanen!",
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
                            Swal.fire(`Gagal ${error.status}`, error.response.data.message, 'error');
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
                        Swal.fire('Berhasil', response.data.message, 'success');
                        btnSpinerFuncs.resetBtnSubmit(modalElement);
                    })
                    .catch(error => {
                        console.log(error.response.data); // Tambahkan ini
                        Swal.fire('Gagal', error.response.data.message || 'Validasi gagal', 'error');
                        btnSpinerFuncs.resetBtnSubmit(modalElement);
                    });
            };
            btnFalse.onclick = () => {
                modal.hide();
            };
        };

        $(document).on('click', '.btn_add', function () {
            const modalElement = document.querySelector('#modal-yes-no');
            const modal = new coreui.Modal(modalElement);
            axios.get('{{ route('admin.keahlian.tag_keahlian.create') }}')
                .then(response => {
                    modalElement.querySelector('.modal-title').innerHTML = 'Tambah Tag Keahlian';
                    modalElement.querySelector('.modal-body').innerHTML = response.data;
                    addEditHandler(modalElement, modal);
                    modal.show();
                })
                .catch(error => {
                    console.error('Gagal mengambil form:', error);
                    Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                });
        });

        $(document).on('click', '.btn_edit', function () {
            const keahlian_id = $(this).data('keahlian_id');
            const modalElement = document.querySelector('#modal-yes-no');
            const modal = new coreui.Modal(modalElement);
            axios.get('{{ route('admin.keahlian.tag_keahlian.edit', ':id') }}'.replace(':id', keahlian_id))
                .then(response => {
                    modalElement.querySelector('.modal-title').innerHTML = 'Edit Tag Keahlian';
                    modalElement.querySelector('.modal-body').innerHTML = response.data;
                    addEditHandler(modalElement, modal);
                    modal.show();
                })
                .catch(error => {
                    console.error('Gagal ambil data:', error);
                    Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                });
        });

        $(document).on('click', '.btn_show', function () {
            const keahlian_id = $(this).data('keahlian_id');
            const modalElement = document.querySelector('#page-modal');
            const modal = new coreui.Modal(modalElement);
            axios.get('{{ route('admin.keahlian.tag_keahlian.show', ':id') }}'.replace(':id', keahlian_id))
                .then(response => {
                    modalElement.querySelector('.modal-title').innerHTML = 'Detail Tag Keahlian';
                    modalElement.querySelector('.modal-body').innerHTML = response.data;
                    modal.show();
                })
                .catch(error => {
                    console.error('Gagal ambil detail:', error);
                    Swal.fire(`Error ${error.status}`, 'Lihat console', 'error');
                });
        });
    };

    document.addEventListener('DOMContentLoaded', run);
</script>
@endsection
