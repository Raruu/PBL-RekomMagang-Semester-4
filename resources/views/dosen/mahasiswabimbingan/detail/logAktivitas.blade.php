@extends('layouts.app') <!-- atau layout yang kamu pakai -->

@section('title', 'Log Aktivitas Mahasiswa')

@section('content')
<div class="container">
    <h3 class="mb-4">Log Aktivitas Mahasiswa: {{ $pengajuan->profilMahasiswa->nama ?? '-' }}</h3>

    @if($pengajuan->logAktivitas->isEmpty())
        <div class="alert alert-info">Tidak ada log aktivitas untuk mahasiswa ini.</div>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Tanggal Log</th>
                    <th>Aktivitas</th>
                    <th>Kendala</th>
                    <th>Solusi</th>
                    <th>Feedback Dosen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->logAktivitas as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->tanggal_log)->format('d-m-Y') }}</td>
                        <td>{{ $log->aktivitas }}</td>
                        <td>{{ $log->kendala }}</td>
                        <td>{{ $log->solusi }}</td>
                        <td>{{ $log->feedback_dosen ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('dosen.mahasiswabimbingan.detail', $pengajuan->pengajuan_id) }}" class="btn btn-secondary mt-3">Kembali ke Detail Mahasiswa</a>
</div>
@endsection
