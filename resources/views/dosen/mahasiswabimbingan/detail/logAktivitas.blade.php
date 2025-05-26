@extends('layouts.app')

@section('title', 'Log Aktivitas Mahasiswa')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container">
    <h3 class="mb-4">Log Aktivitas Mahasiswa: {{ $pengajuan->profilMahasiswa->nama ?? '-' }}</h3>

    @if($pengajuan->logAktivitas->isEmpty())
    <div class="alert alert-info">Tidak ada log aktivitas untuk mahasiswa ini.</div>
    @else
    <a href="{{ route('dosen.mahasiswabimbingan.detail', $pengajuan->pengajuan_id) }}" class="btn btn-primary mb-4">
        Kembali ke Detail Mahasiswa
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal Log</th>
                <th>Aktivitas</th>
                <th>Kendala</th>
                <th>Solusi</th>
                <th>Feedback Dosen</th>
                <th>Aksi</th>
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
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary open-feedback-modal" data-log-id="{{ $log->log_id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        @if($log->feedback_dosen)
                        <button class="btn btn-sm btn-outline-danger open-delete-modal" data-log-id="{{ $log->log_id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @push('styles')
    <style>
        .table th:first-child,
        .table td:first-child {
            width: 150px;
            text-align: center;
            white-space: nowrap;
        }

        table thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
    @endpush

    @endif
    @endsection
    <!-- Modal Feedback -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="feedbackForm" method="POST" action="{{ route('dosen.logaktivitas.feedback') }}">
                @csrf
                <input type="hidden" name="log_id" id="modalLogId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feedbackModalLabel">Beri / Edit Feedback Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Feedback</label>
                            <textarea name="feedback" id="feedback" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Notifikasi Sukses -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-0 justify-content-center">
                    <h5 class="modal-title text-success" id="successModalLabel">🎉 Berhasil!</h5>
                </div>
                <div class="modal-body">
                    <p id="successMessage">Feedback berhasil disimpan.</p>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Feedback -->
    <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" aria-labelledby="deleteFeedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteFeedbackForm" method="POST" action="{{ route('dosen.logaktivitas.hapusFeedback') }}">
                @csrf
                <input type="hidden" name="log_id" id="deleteLogId">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteFeedbackModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus feedback ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            const deleteFeedbackModal = new bootstrap.Modal(document.getElementById('deleteFeedbackModal'));

            document.querySelectorAll('.open-feedback-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const logId = this.dataset.logId;
                    const row = this.closest('tr');
                    const currentFeedback = row.querySelector('td:nth-child(5)').textContent.trim();

                    document.getElementById('modalLogId').value = logId;
                    document.getElementById('feedback').value = currentFeedback === '-' ? '' : currentFeedback;

                    feedbackModal.show();
                });
            });

            document.querySelectorAll('.open-delete-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const logId = this.dataset.logId;
                    document.getElementById('deleteLogId').value = logId;
                    deleteFeedbackModal.show();
                });
            });

            // Tampilkan notifikasi sukses jika ada
            @if(session('feedback_success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('feedback_success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Oke'
            });
            @endif
        });
    </script>
    @endpush