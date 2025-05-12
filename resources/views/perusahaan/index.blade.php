@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Perusahaan Mitra</h5>
        <a href="{{ route('perusahaan.create') }}" class="btn btn-success btn-sm">+ Tambah Perusahaan</a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-striped table-bordered table-hover table-sm" id="table_perusahaan">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Bidang Industri</th>
                <th>Website</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Lokasi</th> {{-- Tambahan kolom lokasi --}}
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('end')
<script>
    const run = () =>{
    $(document).ready(function() {
        $('#table_perusahaan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('perusahaan.list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'nama_perusahaan' },
                { data: 'bidang_industri' },
                { data: 'website' },
                { data: 'kontak_email' },
                { data: 'kontak_telepon' },
                { data: 'alamat' }, // tambahkan ini untuk kolom lokasi
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: function(data) {
                        return data == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
                    }
                },
                {
                    data: 'aksi',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ]

        });
    });}
    document.addEventListener('DOMContentLoaded', run);
</script>
@endpush
