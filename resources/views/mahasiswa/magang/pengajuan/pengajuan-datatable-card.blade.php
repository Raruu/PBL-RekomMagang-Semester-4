<div class="card-body">
    <div class="d-flex flex-row justify-content-between ">
        <div class="d-flex flex-column gap-2" style="width: 80%">
            <div class="d-flex flex-row gap-2">
                <h6 class="fw-bold mb-0">${row.judul}</h6>
                <span class="badge bg-primary my-auto">
                    ${row.tipe_kerja_lowongan.charAt(0).toUpperCase() + row.tipe_kerja_lowongan.slice(1)}
                </span>
            </div>
            <p class="mb-0"
                style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                ${row.deskripsi}</p>
            <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                ${row.keahlian_lowongan.split(',').map(t=>`<span
                    class="badge bg-secondary">${t.trim()}</span>`).join('')}
            </div>
        </div>
        <div class="d-flex flex-column justify-content-between gap-1 ">
            <div class="d-flex flex-column gap-1">
                <div class="d-flex flex-row justify-content-end">
                    <span
                        class="badge bg-${ row.status == 'disetujui' ? 'success' : (row.status == 'ditolak' ? 'danger' : row.status == 'menunggu' ? 'secondary' : 'info') }">
                        ${row.status_magang == 2 && row.status != 'selesai' ? 'Finishing' :
                        row.status.charAt(0).toUpperCase() + row.status.slice(1)}
                    </span>
                </div>
                <div class="d-flex flex-row justify-content-end ${row.status == 'selesai' ? 'd-none' : ''}">
                    <span
                        class="badge bg-${row.status_magang == 1 ? 'success' : (row.status_magang == 2 ? 'info' : 'secondary') }">
                        ${row.status_magang == 0 ? 'Belum Mulai' : (row.status_magang == 1 ? 'Berlangsung' : 'Selesai')}
                    </span>
                </div>
            </div>
            <p class="fw-bold mb-0 text-muted "><span class="text-muted" style="font-size: 14px">Pengajuan:</span>
                <br />${new Date(row.tanggal_pengajuan).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
                })}
            </p>
        </div>
    </div>
</div>
