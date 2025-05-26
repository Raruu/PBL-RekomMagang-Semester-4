<form id="formEditDosen" action="{{ url('/admin/pengguna/dosen/' . $dosen->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="mb-3">
                <div class="d-inline-block position-relative">
                    @if($dosen->profilDosen->foto_profil)
                        <img src="{{ asset('storage/' . $dosen->profilDosen->foto_profil) }}" alt="Foto Profil"
                            class="rounded-circle border border-2 border-primary shadow-sm"
                            style="width: 150px; height: 150px ; object-fit: cover;">
                    @else
                        <img src="{{ asset('imgs/profile_placeholder.jpg') }}" alt="Foto Profil Default"
                            class="rounded-circle border border-2 border-secondary shadow-sm"
                            style="width: 150px; height: 150px ; object-fit: cover;">
                    @endif
                    <div class="mt-1">
                        <small class="text-muted">Foto Profil</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">NIP</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $dosen->username }}"
                    readonly style="cursor: not-allowed;">
                <small class="text-muted">
                    <i class="fas fa-lock"></i> NIP tidak dapat diubah
                </small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama"
                    value="{{ $dosen->profilDosen->nama ?? '' }}" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
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

    <!-- Hidden fields -->
    <input type="hidden" name="nip" value="{{ $dosen->username }}">
    <input type="hidden" name="lokasi_id" value="{{ $dosen->profilDosen->lokasi_id ?? 1 }}">

    <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">
            <i class="fas fa-times"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</form>