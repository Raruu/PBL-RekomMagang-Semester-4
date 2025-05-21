<div class="d-flex flex-row justify-content-between align-items-center" id="notif-toast-${row.id}">
    <div class="d-flex flex-column gap-1">
        <h6 class="fw-bold text-small mb-0">${row.judul}</h6>
        <p class="text-small mb-0"
            style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 200px;">
            ${row.pesan}
        </p>
        <div class="d-flex flex-row gap-1 flex-wrap">
            <button class="btn btn-outline-primary btn-sm" type="button"
                onclick="notifications.markRead('${row.id}', '${row.link}').then(response => {
                    if ('${row.link}' !== '') {
                        window.location.href = '${row.link}';
                    }
                });">
                ${row.linkTitle}
            </button>
        </div>
    </div>
    <div class="d-flex flex-column align-items-end gap-1">
        #showmarkread
    </div>
</div>
