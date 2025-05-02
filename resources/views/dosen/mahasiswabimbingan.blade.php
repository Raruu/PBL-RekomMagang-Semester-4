@extends('layouts.app') {{-- Ganti dengan layout Anda jika berbeda --}}

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

    {{-- Tabel Mahasiswa Bimbingan --}}
    <div class="card">
        <div class="card-header">
            <strong>{{ $page->title }}</strong>
        </div>
        <div class="card-body">
            <table id="mahasiswaTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Semester</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
            ajax: '{{ route("dosen.index") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_lengkap', name: 'profil.nama_lengkap' },
                { data: 'nim', name: 'profil.nim' },
                { data: 'program_studi', name: 'program.nama_program' },
                { data: 'semester', name: 'profil.semester' },
                { data: 'telepon', name: 'profil.nomor_telefon' },
                { data: 'alamat', name: 'profil.alamat' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
