<div class="mb-3">
    <label for="nama_pengalaman" class="form-label">Nama Pengalaman</label>
    <input type="text" class="form-control" id="nama_pengalaman" name="nama_pengalaman[]" value="">
    <div id="error-nama_pengalaman" class="text-danger"></div>
</div>
<div class="mb-3">
    <label for="deskripsi_pengalaman" class="form-label">Deskripsi Pengalaman</label>
    <input type="text" class="form-control" id="deskripsi_pengalaman" name="deskripsi_pengalaman[]" >
    <div id="error-deskripsi_pengalaman" class="text-danger"></div>
</div>
<div class="mb-3">
    <label for="tag" class="form-label">Tag Pengalaman</label>
    <input type="text" class="form-control" id="tag" name="tag[]" value="">
    <div id="error-tag" class="text-danger"></div>
</div>

<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="tipe_pengalaman[]" id="tipe_pengalaman_kerja" value="kerja"
        checked>
    <label class="form-check-label" for="tipe_pengalaman_kerja">
        Kerja
    </label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="tipe_pengalaman[]" id="tipe_pengalaman_lomba" value="lomba">
    <label class="form-check-label" for="tipe_pengalaman_lomba">
        Lomba
    </label>
</div>
<div id="input-tanggal-kerja" style="display: none;">
    <div class="mb-3">
        <label for="periode_mulai" class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-control" id="periode_mulai" name="periode_mulai[]" value="">
        <div id="error-periode_mulai" class="text-danger"></div>
    </div>
    <div class="mb-3">
        <label for="periode_selesai" class="form-label">Tanggal Akhir</label>
        <input type="date" class="form-control" id="periode_selesai" name="periode_selesai[]" value="">
        <div id="error-periode_selesai" class="text-danger"></div>
    </div>
</div>
<div id="input-file-lomba" style="display: none;">
    <div class="mb-3">
        <label for="path_file" class="form-label">File Lomba</label>
        <input type="file" class="form-control" id="path_file" name="dokumen_file[]" value=""
            accept=".pdf,.doc,.docx">
        <div id="error-path_file" class="text-danger"></div>
    </div>
</div>
