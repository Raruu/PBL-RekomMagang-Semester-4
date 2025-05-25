<form id="formEditMahasiswa" action="{{ route('admin.mahasiswa.update', $mahasiswa->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <!-- Basic Account Information -->
            <div class="mb-3">
                <label for="username" class="form-label">NIM <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $mahasiswa->username }}"
                    required>
                <small class="form-text text-muted">NIM akan digunakan sebagai username login</small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $mahasiswa->email }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <!-- Profile Information -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama"
                    value="{{ $mahasiswa->profilMahasiswa->nama ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="program_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select" id="program_id" name="program_id" required>
                    <option value="">Pilih Program Studi</option>
                    @foreach($programStudi as $program)
                        <option value="{{ $program->program_id }}" {{ ($mahasiswa->profilMahasiswa->program_id ?? '') == $program->program_id ? 'selected' : '' }}>
                            {{ $program->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="">Pilih Semester</option>
                    @for($i = 1; $i <= 14; $i++)
                        <option value="{{ $i }}" {{ ($mahasiswa->profilMahasiswa->semester ?? '') == $i ? 'selected' : '' }}>
                            Semester {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon"
                    value="{{ $mahasiswa->profilMahasiswa->nomor_telepon ?? '' }}">
            </div>
        </div>
    </div>

    <!-- Full Width Fields -->
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat"
                    rows="3">{{ $mahasiswa->profilMahasiswa->alamat ?? '' }}</textarea>
            </div>
        </div>
    </div>

    <!-- Hidden Fields -->
    <input type="hidden" name="lokasi_id" value="{{ $mahasiswa->profilMahasiswa->lokasi_id ?? 1 }}">

    <!-- Action Buttons -->
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</form>

<script>
    // Form validation
    document.getElementById('formEditMahasiswa').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            Swal.fire('Error!', 'Password dan konfirmasi password tidak cocok', 'error');
            return false;
        }

        if (password && password.length < 5) {
            e.preventDefault();
            Swal.fire('Error!', 'Password minimal 5 karakter', 'error');
            return false;
        }
    });

    // Real-time password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function () {
        const password = document.getElementById('password').value;
        const confirmation = this.value;

        if (password && confirmation && password !== confirmation) {
            this.setCustomValidity('Password tidak cocok');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    // Clear confirmation when password changes
    document.getElementById('password').addEventListener('input', function () {
        const confirmation = document.getElementById('password_confirmation');
        if (!this.value) {
            confirmation.value = '';
            confirmation.classList.remove('is-invalid');
        }
    });
</script>