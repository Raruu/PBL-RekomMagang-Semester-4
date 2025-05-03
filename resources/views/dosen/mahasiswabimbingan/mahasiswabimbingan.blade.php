@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
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

    {{-- Tabel Mahasiswa Bimbingan Magang --}}
    <div class="card">
        <div class="card-header">
            <strong>{{ $page->title }}</strong>
        </div>
        <div class="card-body">
            <table id="mahasiswaTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Lowongan ID</th>
                        <th>Nama Dosen</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanMagang as $index => $pengajuan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}</td>
                            <td>{{ $pengajuan->lowongan_id }}</td>
                            <td>{{ $pengajuan->dosen->nama_lengkap ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($pengajuan->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(function () {
        $('#mahasiswaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("dosen.mahasiswabimbingan") }}', // Route untuk DataTables AJAX
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_mahasiswa', name: 'mahasiswa.nama_lengkap' },
                { data: 'lowongan_id', name: 'lowongan_id' },
                { data: 'nama_dosen', name: 'dosen.nama_lengkap' },
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan' },
                { data: 'status', name: 'status' },
            ]
        });
    });
</script>
@endsection
