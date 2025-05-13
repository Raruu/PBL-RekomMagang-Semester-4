@extends('layouts.app')

@section('title', $page->title)
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

    <div class="d-flex justify-content-between align-items-center mb-3">

        <a href="{{ route('dosen.mahasiswabimbingan') }}" class="btn btn-primary text-white">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>

        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#logAktivitasModal">
            <i class="fas fa-clock me-1"></i> Log Aktivitas
        </button>



        <!-- Modal log aktivitas -->
        <div class="modal fade" id="logAktivitasModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content" id="logAktivitasModalContent">
                    @include('dosen.mahasiswabimbingan.detail.logAktivitasModal')
                </div>
            </div>
        </div>


    </div>
    <div class="row">
        {{-- Card: Detail Mahasiswa --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>Detail Mahasiswa</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="40%">Nama Mahasiswa</th>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card: Detail Lowongan --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <strong>Detail Lowongan</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="40%">Judul Lowongan</th>
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
    </div>


</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btnLogAktivitas', function() {
        var pengajuanId = $(this).data('id');
        var url = "{{ url('/dosen/mahasiswabimbingan') }}/" + pengajuanId + "/logAktivitas";
        console.log('URL:', url); // Cek URL
        $('#logAktivitasModal').modal('show');
        $('#logAktivitasModalContent').html(`
            <div class="modal-header">
                <h5 class="modal-title">Memuat...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#logAktivitasModalContent').html(response);
            },
            error: function() {
                $('#logAktivitasModalContent').html(`
                    <div class="modal-header">
                        <h5 class="modal-title">Gagal Memuat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">Terjadi kesalahan saat memuat data log aktivitas.</div>
                    </div>
                `);
            }
        });
    });
</script>
@endsection