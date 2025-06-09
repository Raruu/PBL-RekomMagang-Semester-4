<div class="card-body">
    <form action="{{ route('admin.bidang_industri.update', ['id' => $bidangIndustri->bidang_id]) }}" method="POST"
        class="d-flex flex-column">
        @csrf
        @method('PUT')
        <div class="flex-fill">
            <label for="nama" class="form-label">Nama Bidang <span class="text-danger">*</span></label>
            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                value="{{ $bidangIndustri->nama }}" required>
            <div id="error-nama" class="text-danger"></div>
        </div>
    </form>
</div>
