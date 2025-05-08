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
 
 <div class="mb-3 text-start    ">
        <a href="{{ route('dosen.mahasiswabimbingan') }}" class="btn btn-primary text-white" >
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card">
    <div class="card-header bg-primary text-white">
        <strong>Detail Mahasiswa Bimbingan</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="30%">Nama Mahasiswa</th>
                    <td>{{ $pengajuan->profilMahasiswa->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $pengajuan->profilMahasiswa->nim ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Program Studi</th>
                    <td>{{ $pengajuan->profilMahasiswa->programStudi->nama_program ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Semester</th>
                    <td>{{ $pengajuan->profilMahasiswa->semester ?? '-' }}</td>
                </tr>
                <tr>
                    <th>IPK</th>
                    <td>{{ $pengajuan->profilMahasiswa->ipk ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Judul Lowongan</th>
                    <td>{{ $pengajuan->lowonganMagang->judul_posisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $pengajuan->lowonganMagang->lokasi->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Perusahaan</th>
                    <td>{{ $pengajuan->lowonganMagang->perusahaan->nama_perusahaan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($pengajuan->status) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection
