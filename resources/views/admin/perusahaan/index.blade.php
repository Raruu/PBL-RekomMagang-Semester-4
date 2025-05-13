@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <h4>{{ $breadcrumb->title }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($breadcrumb->list as $item)
                            <li class="breadcrumb-item">{{ $item }}</li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">{{ $page->title }}</h6>
                        <a href="{{ url('/admin/perusahaan/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Perusahaan
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="perusahaanTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Perusahaan</th>
                                        <th class="text-center">Bidang Industri</th>
                                        <th class="text-center">Website</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Telepon</th>
                                        <th class="text-center">Lokasi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@endpush

@push('end')
    <script type="module">
        const run = () => {
            $(function () {
                $('#perusahaanTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('/admin/perusahaan/') }}",
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
                            data: 'website', 
                            name: 'website' 
                        },
                        { 
                            data: 'kontak_email', 
                            name: 'kontak_email' 
                        },
                        { 
                            data: 'kontak_telepon', 
                            name: 'kontak_telepon' 
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
                            searchable: false 
                        },
                    ],
                });

                // Konfirmasi sebelum menghapus
                $(document).on('submit', '.delete-form', function (e) {
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
                            this.submit();
                        }
                    });
                });
            });
        };

        // Perbaikan untuk Toggle Status menggunakan AJAX
        $(document).on('click', '.toggle-status-btn', function () {
            const perusahaan_id = $(this).data('perusahaan_id');
            const nama_perusahaan = $(this).data('nama_perusahaan');

            console.log('Toggle button clicked for user:', perusahaan_id, nama_perusahaan);

            Swal.fire({
                title: 'Ubah Status Akun?',
                text: `Anda yakin ingin mengubah status akun ${nama_perusahaan}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tambahkan logging untuk debug
                    console.log('Sending AJAX request to:', `/admin/perusahaan/${perusahaan_id}/toggle-status`);

                    $.ajax({
                        url: `/admin/perusahaan/${perusahaan_id}/toggle-status`,
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            console.log('Success response:', res);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#perusahaanTable').DataTable().ajax.reload(null,
                                false); // reload tanpa reset pagination
                        },
                        error: function (xhr) {
                            console.error('Error response:', xhr);
                            Swal.fire({
                                title: 'Gagal!',
                                text: xhr.responseJSON?.error || 'Terjadi kesalahan.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endpush