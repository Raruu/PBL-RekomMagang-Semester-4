 <div class="card-body">
     <div class="d-flex flex-row justify-content-between align-items-center">
         <div class="d-flex flex-column gap-2" style="width: 80%">
             <div class="d-flex flex-row gap-2">
                 <h6 class="fw-bold mb-0">${row.judul}</h6>
                 <span class="badge bg-primary my-auto">
                     ${row.tipe_kerja_lowongan.charAt(0).toUpperCase() + row.tipe_kerja_lowongan.slice(1)}
                 </span>
                 ${row.is_diajukan ? '<span class="badge bg-warning my-auto">Sudah Diajukan</span>' : ''}
             </div>
             <p class="mb-0"
                 style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                 ${row.deskripsi}</p>
             <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
                 ${row.keahlian_lowongan.split(',').map(t=>`<span
                     class="badge bg-secondary">${t.trim()}</span>`).join('')}
             </div>
         </div>
         <div class="d-flex flex-column align-items-end gap-1">
             <span
                 class="badge  bg-${ row.skor > 0.7 ? 'success' : (row.skor > 0.5 ? 'warning' : 'danger') }">${(row.skor
                 * 100).toFixed(2)}%
             </span>
             <p class="fw-bold mb-0 text-muted ">${row.batas_pendaftaran} Hari Lagi</p>
             <span class="badge bg-${row.gaji > 0 ? 'info' : 'danger'} ">
                 ${row.gaji > 0 ? 'Rp. ' + row.gaji : 'Tidak ada gaji'}
             </span>
         </div>
     </div>
 </div>
