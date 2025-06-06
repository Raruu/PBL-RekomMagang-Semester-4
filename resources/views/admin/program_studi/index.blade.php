@extends('layouts.app')

@section('title', 'Program Studi')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-column mb-3 header-program">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-graduation-cap text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">Program Studi</h2>
                                <p class="text-body-secondary mb-0">Kelola semua program studi dengan mudah</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: <span id="record-count" class="fw-bold">0</span> program
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-3">
            <!-- Sebelah Kiri -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-primary btn-action d-flex align-items-center" id="btn-tambah">
                    <i class="fas fa-plus me-2"></i>
                    <span>Tambah Program Studi</span>
                </button>
                <button type="button" class="btn btn-success btn-action d-flex align-items-center" id="btn-refresh">
                    <i class="fas fa-sync-alt me-2"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="d-flex flex-column pb-4">
            <div class="card shadow-sm table-card">
                <div class="card-header border-bottom">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-table me-2 text-primary"></i>
                            <h5 class="mb-0 fw-semibold">Daftar Program Studi</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive table-container">
                        <table class="table table-hover table-bordered table-striped mb-0" id="programTable"
                            style="width: 100%">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th>Nama Program</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalProgram" tabindex="-1" aria-labelledby="modalProgramLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formProgram">
                @csrf
                <input type="hidden" name="program_id" id="program_id">
                <div class="modal-content custom-modal-content">
                    <div class="modal-header bg-primary text-white">
                        <div class="icon-header-wrapper">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5 class="modal-title" id="modalProgramLabel">Tambah Program Studi</h5>
                        <button type="button" class="btn-close btn-close-white" id="btn-close-modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_program" class="form-label fw-semibold">Nama Program</label>
                            <input type="text" class="form-control" id="nama_program" name="nama_program" required>
                            <div class="invalid-feedback" id="error-nama_program"></div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
                            <div class="invalid-feedback" id="error-deskripsi"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" id="btn-cancel-modal">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .header-program {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: var(--cui-body-bg);
            border-bottom: 1px solid var(--cui-border-color);
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            background-color: var(--cui-primary-bg-subtle);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action {
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }

        .table-card .card-header {
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--cui-border-color);
        }

        .table-header th {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .custom-modal-content {
            border-radius: 1.1rem;
            border: none;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .icon-header-wrapper {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            background: #f8fafd;
            border-top: 1px solid #e2e8f0;
        }

        .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-label {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .btn {
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .badge {
            border-radius: 0.5rem;
        }

        .table-container {
            border-radius: 0 0 1rem 1rem;
            overflow: hidden;
        }

        .btn-edit {
            border: none;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-delete {
            border: none;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            color: white;
        }
    </style>
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalElement = document.getElementById('modalProgram');
            let modalInstance = coreui.Modal.getOrCreateInstance(modalElement);

            const table = $('#programTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('program_studi.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_program',
                    name: 'nama_program'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                }
                ],
                columnDefs: [{
                    targets: [0, 3],
                    className: 'text-center'
                }],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    processing: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data...</div>',
                    search: "Search:",
                    infoEmpty: "Tidak ada data yang tersedia",
                    emptyTable: "Tidak ada data program studi yang tersedia",
                },
                drawCallback: function (settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function (index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
            });

            $('#btn-refresh').on('click', function () {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.html('<i class="fas fa-spinner fa-spin me-2"></i><span>Refreshing...</span>');
                $btn.prop('disabled', true);

                table.ajax.reload(function () {
                    setTimeout(() => {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }, 500);
                });
            });

            document.getElementById('btn-tambah').addEventListener('click', function () {
                $('#formProgram')[0].reset();
                $('#program_id').val('');
                $('#modalProgramLabel').text('Tambah Program Studi');
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
                $('.icon-header-wrapper i').removeClass().addClass('fas fa-plus');

                modalInstance.show();
            });

            document.getElementById('btn-close-modal').addEventListener('click', function () {
                modalInstance.hide();
            });

            document.getElementById('btn-cancel-modal').addEventListener('click', function () {
                modalInstance.hide();
            });

            document.getElementById('formProgram').addEventListener('submit', function (e) {
                e.preventDefault();
                let id = $('#program_id').val();
                let url = id ? `/admin/program_studi/${id}` : `/admin/program_studi`;
                let method = id ? 'PUT' : 'POST';
                let formData = $(this).serialize();

                const submitBtn = $('#btn-simpan');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function (res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'animated fadeIn'
                            }
                        });
                        modalInstance.hide();
                        table.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        $('.invalid-feedback').text('');
                        $('.form-control').removeClass('is-invalid');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (let key in errors) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#error-${key}`).text(errors[key][0]);
                            }
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan.',
                                icon: 'error',
                                customClass: {
                                    popup: 'animated fadeIn'
                                }
                            });
                        }
                    },
                    complete: function () {
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // Edit
            $('#programTable').on('click', '.btn-edit', function () {
                let id = $(this).data('id');
                $.get(`/admin/program_studi/${id}/edit`, function (res) {
                    $('#program_id').val(res.program.program_id);
                    $('#nama_program').val(res.program.nama_program);
                    $('#deskripsi').val(res.program.deskripsi);
                    $('#modalProgramLabel').text('Edit Program Studi');
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');
                    $('.icon-header-wrapper i').removeClass().addClass('fas fa-edit');

                    modalInstance.show();
                });
            });

            // Delete
            $('#programTable').on('click', '.btn-delete', function () {
                let id = $(this).data('id');
                let namaProgram = $(this).data('nama_program') || '';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data program studi "${namaProgram}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'animated fadeIn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalLoading('Mengirim data ke server...');
                        $.ajax({
                            url: `{{ route('program_studi.destroy', ['id' => ':id']) }}`.replace(':id', id),
                            type: 'DELETE',                          
                            success: function (res) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.message || 'Terjadi kesalahan.',
                                    icon: 'error',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush