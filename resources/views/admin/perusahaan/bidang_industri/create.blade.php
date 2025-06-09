<div class="card-body">
    <form action="{{ route('admin.bidang_industri.store') }}" method="POST" class="d-flex flex-column">
        @csrf
        <div class="flex-fill">
            <label for="nama" class="form-label">Nama Bidang <span class="text-danger">*</span></label>
            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                value="" required>
            <div id="error-nama" class="text-danger"></div>
        </div>
    </form>
</div>
