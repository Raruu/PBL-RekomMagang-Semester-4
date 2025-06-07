<div class="d-flex flex-column gap-1 flex-fill background-hoverable p-3" style="cursor: pointer; "
    onClick="editPengalaman(this)">
    <h7 class="fw-bold mb-0 text-wrap" id="display-nama_pengalaman">{{ $pengalaman->nama_pengalaman }}</h7>
    <p class="mb-0" id="display-deskripsi_pengalaman">{{ $pengalaman->deskripsi_pengalaman }}</p>
    <input type="hidden" name="pengalaman_id[]" value="{{ $pengalaman->pengalaman_id }}">
    <input type="hidden" name="nama_pengalaman[]" value="{{ $pengalaman->nama_pengalaman }}">
    <input type="hidden" name="deskripsi_pengalaman[]" value="{{ $pengalaman->deskripsi_pengalaman }}">
    <input type="hidden" name="tag[]"
        value="{{ json_encode($pengalaman->pengalamanTag->map(fn($tag) => ['value' => $tag->keahlian->nama_keahlian])->toArray()) }}">
    <input type="hidden" name="tipe_pengalaman[]" value="{{ $pengalaman->tipe_pengalaman }}">
    <input type="hidden" name="periode_mulai[]" value="{{ $pengalaman->periode_mulai }}">
    <input type="hidden" name="periode_selesai[]" value="{{ $pengalaman->periode_selesai }}">
    <input type="file" class="d-none" name="dokumen_file[]">
    <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
    <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
        @foreach ($pengalaman->pengalamanTag as $tag)
            <span class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
        @endforeach
    </div>
</div>
