 <div class="card-body">
     <div class="d-flex flex-row justify-content-between align-items-center">
         <div class="d-flex flex-column gap-2">
             <h6 class="fw-bold mb-0">${row.judul}</h6>
             <p class="mb-0">${row.pesan}</p>
             <div class="d-flex flex-row gap-1 flex-wrap">
                 ${row.linkTitle == '' ? '':
                 `<button class="btn btn-outline-primary btn-sm" type="button"
                     onclick="notificationMarkRead('${row.id}', '${row.link}')">
                     ${row.linkTitle}
                 </button>`}
             </div>
         </div>
         <div class="d-flex flex-column align-items-end gap-1">
             #showmarkread
         </div>
     </div>
 </div>
