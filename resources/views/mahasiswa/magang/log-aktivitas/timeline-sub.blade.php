<div class="timeline-item ms-4 ">
    <div class="timeline-line-horizontal"></div>
    <div class="timeline-marker sub-marker secondary"></div>
    <div class="d-none">
        <div name="kendala">${log.kendala}</div>
        <div name="solusi">${log.solusi}</div>
        <div name="feedback_dosen">${log.feedback_dosen}</div>
        <div name="log_id">${log.log_id}</div>
        <div name="tanggal_log">${key}</div>
        <div name="jam_kegiatan">${log.jam_kegiatan}</div>
    </div>
    <div class="timeline-date">Jam: ${new Date(`${key} ${log.jam_kegiatan}`).toLocaleTimeString('id-ID', { hour:
        '2-digit', minute: '2-digit' })}
    </div>
    <div class="timeline-content bg-light hover d-flex flex-row justify-content-between ${ hasAdditionalInfo ? 'primary-line ' : 'secondary-line' }"
        onclick="show(this)">
        <div name="aktivitas" class="d-flex align-items-center justify-content-center">
            ${log.aktivitas}</div>
        @if ($pengajuanMagang->status != 'selesai')
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation(); edit(this)">
                <i class="fas fa-edit"></i>
            </button>
        @endif
    </div>
</div>
