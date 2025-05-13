<div class="modal-header">
    <h5 class="modal-title">Log Aktivitas Mahasiswa</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if($pengajuan->logAktivitas->isEmpty())
        <p>Tidak ada log aktivitas untuk mahasiswa ini.</p>
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
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
