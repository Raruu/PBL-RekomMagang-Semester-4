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
                        <th>Lowongan</th>
                        <th>Dosen Pembimbing</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanMagang as $index => $pengajuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengajuan->profilMahasiswa->nama ?? '-' }}</td>
                        <td>{{ $pengajuan->lowonganMagang->judul_posisi }}</td>
                        <td>{{ $pengajuan->profilDosen->nama ?? '-' }}</td>
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

<script>
    const run = () => {
        // code
        $(function() {
            $('#mahasiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("dosen.mahasiswabimbingan") }}', // Route untuk DataTables AJAX
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa'
                    },
                    {
                        data: 'lowongan_id',
                        name: 'lowongan_id'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen'
                    },

                    {
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ]
            });
        });
    }


    document.addEventListener('DOMContentLoaded', run);
</script>
@endsection