<div id="error-dokumen_input" class="text-danger"></div>
<div class="input-group">
    <span class="input-group-text">Nama Dokumen</span>
    <input type="text" class="form-control" name="jenis_dokumen[]" placeholder="Jenis Dokumen" value="Dokumen Tambahan"
        required>
</div>
<div class="input-group">
    <input type="text" class="form-control file_name" readonly disabled>
    <button class="btn btn-outline-primary button_preview_file" type="button">
        <i class="fas fa-eye"></i>
    </button>
</div>
<button type="button" class="btn btn-outline-danger button_delete_file"
    onClick="this.parentElement.remove(); notifyDocumentChanged();">
    <svg class="icon">
        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-trash') }}">
        </use>
    </svg>
</button>
