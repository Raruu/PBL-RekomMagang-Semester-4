<div class="d-flex flex-column gap-0 flex-fill background-hoverable p-3" onClick="openKeahlian(this)">
    <div class="d-flex flex-column gap-1 flex-fill">
        <h7 class="fw-bold mb-0" id="display-nama_pengalaman">
            {{ $pengalaman->nama_pengalaman }}
        </h7>
        <p class="mb-0" id="display-deskripsi_pengalaman">
            {{ $pengalaman->deskripsi_pengalaman }}
        </p>
        <div class="d-flex flex-column py-3 d-none" id="display-deskripsi_pengalaman_textarea">
            <textarea class="form-control "  rows="3" readonly>{{ $pengalaman->deskripsi_pengalaman }}</textarea>
        </div>
        <input type="hidden" name="tipe_pengalaman[]" value="{{ $pengalaman->tipe_pengalaman }}">
        <input type="hidden" name="periode_mulai[]" value="{{ $pengalaman->periode_mulai }}">
        <input type="hidden" name="periode_selesai[]" value="{{ $pengalaman->periode_selesai }}">
        <div class="d-none" id="path_file">{{ $pengalaman->path_file }}</div>
        <div class="d-flex flex-row gap-1 flex-wrap" id="display-tag">
            @foreach ($pengalaman->pengalamanTag as $tag)
                <span class="badge badge-sm bg-info _badge_keahlian">{{ $tag->keahlian->nama_keahlian }}</span>
            @endforeach
        </div>
    </div>
</div>
