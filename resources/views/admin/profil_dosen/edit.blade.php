<form id="formEditDosen" action="{{ url('/admin/pengguna/dosen/' . $dosen->user_id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">NIP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $dosen->username }}"
                    required>
                <small class="text-muted">NIP digunakan sebagai username untuk login</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $dosen->email }}" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="password" class="form-label">Password
                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                </label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama"
                    value="{{ $dosen->profilDosen->nama ?? '' }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="program_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select" id="program_id" name="program_id" required>
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($programStudi as $prodi)
                        <option value="{{ $prodi->program_id }}" {{ ($dosen->profilDosen->program_id ?? '') == $prodi->program_id ? 'selected' : '' }}>
                            {{ $prodi->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Hidden fields -->
    <input type="hidden" name="nip" value="{{ $dosen->username }}">
    <input type="hidden" name="lokasi_id" value="{{ $dosen->profilDosen->lokasi_id ?? 1 }}">

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
            <i class="fas fa-times"></i> Tutup
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</form>