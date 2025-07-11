<input type="hidden" name="pengalaman_id[]" value="">
<div class="mb-3">
    <label for="nama_pengalaman" class="form-label">Nama Pengalaman <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="nama_pengalaman" name="nama_pengalaman[]" value="">
    <div id="error-nama_pengalaman" class="text-danger"></div>
</div>
<div class="mb-3">
    <label for="deskripsi_pengalaman" class="form-label">Deskripsi Pengalaman <span class="text-danger">*</span></label>
    <textarea class="form-control" id="deskripsi_pengalaman" name="deskripsi_pengalaman[]" rows="3"></textarea>
    <div id="error-deskripsi_pengalaman" class="text-danger"></div>
</div>
<div class="mb-3">
    <label for="tag" class="form-label">Tag Pengalaman</label>
    <input type="text" class="form-control" id="tag" name="tag[]" value="">
    <div id="error-tag" class="text-danger"></div>
</div>

<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="tipe_pengalaman" id="tipe_pengalaman_kerja" value="kerja"
        checked>
    <label class="form-check-label" for="tipe_pengalaman_kerja">
        Kerja
    </label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="tipe_pengalaman" id="tipe_pengalaman_lomba" value="lomba">
    <label class="form-check-label" for="tipe_pengalaman_lomba">
        Lomba
    </label>
</div>
<div id="input-tanggal-kerja" style="display: none;" class="mt-2">
    <div class="mb-3">
        <label for="periode_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="periode_mulai" name="periode_mulai[]" value="">
        <div id="error-periode_mulai" class="text-danger"></div>
    </div>
    <div class="mb-3">
        <label for="periode_selesai" class="form-label">Tanggal Akhir <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="periode_selesai" name="periode_selesai[]" value="">
        <div id="error-periode_selesai" class="text-danger"></div>
    </div>
</div>
<div id="input-file-lomba" style="display: none;" class="mt-2">
    <div class="mb-3">
        <label for="path_file" class="form-label">File Lomba <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="file" class="form-control" id="path_file" name="dokumen_file[]" value=""
                accept=".pdf">
            <button class="btn btn-outline-primary" type="button" id="button-preview-file" style="display: none;">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div id="error-dokumen_file" class="text-danger"></div>
    </div>
</div>
<div class="d-none" id="path_file_modal"></div>
