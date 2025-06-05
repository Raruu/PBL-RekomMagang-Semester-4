<div class="card-body">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.keahlian.tag_keahlian.update', $tag_keahlian->keahlian_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_keahlian" class="form-label">Nama keahlian <span
                            class="text-danger">*</span></label>
                    <input type="nama_keahlian" class="form-control @error('nama_keahlian') is-invalid @enderror"
                        id="nama_keahlian" name="nama_keahlian"
                        value="{{ old('nama_keahlian', $tag_keahlian->nama_keahlian) }}" required>
                    <div id="error-nama_keahlian" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                        <option value="" disabled>-- Pilih Nama Kategori --</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->kategori_id }}"
                                {{ $tag_keahlian->kategori_id == $kategori->kategori_id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-kategori_id" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <input type="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                        name="deskripsi" value="{{ old('deskripsi', $tag_keahlian->deskripsi) }}" required>
                    <div id="error-deskripsi" class="text-danger"></div>
                </div>
            </div>
        </div>
    </form>
</div>
