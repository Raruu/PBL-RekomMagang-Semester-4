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
                <tbody>
                    @foreach ($pengajuanMagang as $index => $pengajuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengajuan->profilMahasiswa->nama ?? '-' }}</td>
                        <td>{{ $pengajuan->lowonganMagang->judul_posisi }}</td>
                        <td>{{ $pengajuan->profilDosen->nama ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($pengajuan->status) }}</td>
                        <td>
                            <a href="{{ route('dosen.mahasiswabimbingan.detail', $pengajuan->pengajuan_id) }}" class="btn btn-sm btn-primary">Detail</a>
                        </td>
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
                ajax: '{{ route("dosen.mahasiswabimbingan") }}', 
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    }


    document.addEventListener('DOMContentLoaded', run);
</script>
@endsection