<form id="formEditDosen" action="{{ url('/admin/pengguna/dosen/' . $dosen->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4">
                @if($dosen->profilDosen && $dosen->profilDosen->foto_profil)
                    <img src="{{ asset('storage/' . $dosen->profilDosen->foto_profil) }}" 
                        alt="Foto Profil" class="img-thumbnail rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile"
                        class="img-thumbnail rounded-circle"
                        style="width: 150px; height: 150px; object-fit: cover;">
                @endif

                <div class="mt-2">
                    <small class="text-muted d-block">Foto Profil</small>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ $dosen->username }}" readonly style="cursor: not-allowed;">
                                <small class="text-muted"><i class="fas fa-lock"></i> NIP tidak dapat diubah</small>
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="{{ $dosen->profilDosen->nama ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="program_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <select class="form-select" id="program_id" name="program_id" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach($programStudi as $prodi)
                                        <option value="{{ $prodi->program_id }}" 
                                            {{ ($dosen->profilDosen->program_id ?? '') == $prodi->program_id ? 'selected' : '' }}>
                                            {{ $prodi->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $dosen->email }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="nip" value="{{ $dosen->username }}">
                        <input type="hidden" name="lokasi_id" value="{{ $dosen->profilDosen->lokasi_id ?? 1 }}">
                    </div>

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Preview image when file is selected
    document.getElementById('foto_profil').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Validate password confirmation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        if (password && confirmation && password !== confirmation) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    document.getElementById('password').addEventListener('input', function() {
        const confirmation = document.getElementById('password_confirmation');
        if (confirmation.value) {
            confirmation.dispatchEvent(new Event('input'));
        }
    });
</script>
