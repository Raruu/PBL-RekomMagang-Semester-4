 <div class="card-body">
     <div class="d-flex flex-row justify-content-between align-items-start">
         <div class="d-flex flex-column gap-2">
             <div class="d-flex flex-row gap-2">
                 <h6 class="fw-bold mb-0">${row.judul}</h6>
                 <span class="badge bg-primary my-auto">
                     ${row.tipe_kerja_lowongan.charAt(0).toUpperCase() + row.tipe_kerja_lowongan.slice(1)}
                 </span>
             </div>
             <p class="mb-0">${row.deskripsi}</p>
             <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                 ${row.keahlian_lowongan.split(',').map(t=>`<span
                     class="badge bg-secondary">${t.trim()}</span>`).join('')}
             </div>
         </div>
         <div class="d-flex flex-column gap-1">
             <span
                 class="badge bg-${ row.status == 'disetujui' ? 'success' : (row.status == 'ditolak' ? 'danger' : row.status == 'menunggu' ? 'secondary' : 'info') }">
                 ${row.status.charAt(0).toUpperCase() + row.status.slice(1)}
             </span>
         </div>
     </div>
 </div>
