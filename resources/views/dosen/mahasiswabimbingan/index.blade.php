@extends('layouts.app')

@section('title', $page->title)

@section('content-top')
<div class="container">
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
                        <th>Lowongan</th>
                        <th>Dosen Pembimbing</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
     const run = () => {
        $('#mahasiswaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("dosen.mahasiswabimbingan") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_mahasiswa', name: 'nama_mahasiswa' },
                { data: 'lowongan', name: 'lowongan' },
                { data: 'nama_dosen', name: 'nama_dosen' },
                { data: 'tanggal_pengajuan', name: 'tanggal_pengajuan' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    };
    document.addEventListener('DOMContentLoaded', function() {
        run();
    });
</script>
@endsection




