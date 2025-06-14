<div class="card-body">
    <form action="{{ route('admin.keahlian.tag_keahlian.store') }}" method="POST" class="d-flex flex-column">
        @csrf

        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Keahlian <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                   id="nama_kategori" name="nama_keahlian" value="{{ old('nama_kategori') }}" required>
            @error('nama_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kategori_id" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
            <select class="form-control @error('kategori_id') is-invalid @enderror"
                id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $kategori)
                <option value="{{ $kategori->kategori_id }}">
                    {{ $kategori->nama_kategori }}
                </option>
                @endforeach
            </select>
            @error('kategori_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                      id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
