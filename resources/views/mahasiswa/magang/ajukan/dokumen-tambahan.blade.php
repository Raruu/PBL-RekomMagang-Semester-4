<div class="input-group">
    <span class="input-group-text">Jenis Dokumen</span>
    <input type="text" class="form-control" name="jenis_dokumen[]" placeholder="Jenis Dokumen" value="Dokumen Tambahan" required>
</div>
<div class="input-group">
    <input type="text" class="form-control" id="file_name" readonly disabled>
    <button class="btn btn-outline-primary" type="button" id="button-preview-file">
        <svg class="icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-external-link') }}">
            </use>
        </svg>
    </button>
</div>
<button type="button" class="btn btn-outline-danger" id="button-delete-file" onClick="this.parentElement.remove();">
    <svg class="icon">
        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-trash') }}">
        </use>
    </svg>
</button>
