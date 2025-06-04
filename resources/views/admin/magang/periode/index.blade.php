@extends('layouts.app')

@section('title', 'Manajemen Periode Lowongan')

@section('content-top')
    <div class="container-fluid px-4">
        <div class="d-flex flex-column mb-3 header-lowongan">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="fas fa-calendar-alt text-primary fs-3"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">Manajemen Periode Lowongan</h2>
                                <p class="text-body-secondary mb-0">Kelola tanggal mulai dan selesai lowongan magang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm table-card mb-4">
                    <div class="card-header belum border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper times me-3">
                                <i class="fas fa-times-circle mb-0 me-2"></i>
                            </div>
                            <h5 class="mb-0 fw-semibold">Belum Ada Tanggal Mulai/Selesai</h5>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-bordered table-striped mb-3 align-middle"
                                id="periodeTableBelum" style="width: 100%">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Judul Lowongan</th>
                                        <th>Perusahaan</th>
                                        <th class="text-center">Tanggal Mulai</th>
                                        <th class="text-center">Tanggal Selesai</th>
                                        <th class="text-center" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm table-card mb-4">
                    <div class="card-header selesai border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper check me-3">
                                <i class="fas fa-check-circle mb-0 me-2"></i>
                            </div>
                            <h5 class="mb-0 fw-semibold">Sudah Ada Tanggal Mulai &amp; Selesai</h5>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive table-container">
                            <table class="table table-hover table-bordered table-striped mb-3 align-middle"
                                id="periodeTableSudah" style="width: 100%">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Judul Lowongan</th>
                                        <th>Perusahaan</th>
                                        <th class="text-center">Tanggal Mulai</th>
                                        <th class="text-center">Tanggal Selesai</th>
                                        <th class="text-center" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.magang.periode.edit')
@endsection

@push('styles')
    @vite (['resources/css/lowongan/index.css'])
@endpush

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#periodeTableBelum').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.manajemen_periode.index') }}',
                    type: 'GET',
                    data: { tipe: 'belum' }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                    { data: 'judul_lowongan', name: 'judul_lowongan' },
                    { data: 'perusahaan', name: 'perusahaan' },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai', className: 'text-center' },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ],
                responsive: true,
                columnDefs: [
                    { targets: 0, width: '50px' },
                    { targets: [3, 4, 5], className: 'text-center' },
                    { targets: 5, width: '120px' }
                ],
                drawCallback: function (settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function (index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
            });
            $('#periodeTableSudah').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.manajemen_periode.index') }}',
                    type: 'GET',
                    data: { tipe: 'sudah' }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                    { data: 'judul_lowongan', name: 'judul_lowongan' },
                    { data: 'perusahaan', name: 'perusahaan' },
                    { data: 'tanggal_mulai', name: 'tanggal_mulai', className: 'text-center' },
                    { data: 'tanggal_selesai', name: 'tanggal_selesai', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ],
                responsive: true,
                columnDefs: [
                    { targets: 0, width: '50px' },
                    { targets: [3, 4, 5], className: 'text-center' },
                    { targets: 5, width: '120px' }
                ],
                drawCallback: function (settings) {
                    $('#record-count').text(settings._iRecordsDisplay);
                    $(this.api().table().body()).find('tr').each(function (index) {
                        $(this).css('animation', `fadeInUp 0.3s ease forwards ${index * 0.05}s`);
                    });
                },
            });
            // Ubah tombol edit di DataTables agar memunculkan modal
            $(document).on('click', '.btn-edit-periode', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                const mulai = $(this).data('mulai');
                const selesai = $(this).data('selesai');
                $('#editLowonganId').val(id);
                $('#editTanggalMulai').val(mulai);
                $('#editTanggalSelesai').val(selesai);
                modalEditPeriode.show();
            });
        });
    </script>
@endpush