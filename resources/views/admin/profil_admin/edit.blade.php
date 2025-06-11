<form id="formEditAdmin" action="{{ url('/admin/pengguna/admin/' . $admin->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4">
                @if($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                    <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}" 
                         alt="Foto Profil" class="img-thumbnail rounded-circle" id="previewImage"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile"
                         class="img-thumbnail rounded-circle" id="previewImage"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @endif

                <div class="mt-3">
                    <label for="foto_profil" class="form-label">Ubah Foto Profil</label>
                    <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                           accept="image/jpeg,image/png,image/jpg">
                    <div class="form-text">Format: JPEG, PNG, JPG. Maksimal 2MB.</div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ $admin->username }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $admin->email }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="{{ $admin->profilAdmin->nama ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" 
                                   value="{{ $admin->profilAdmin->nomor_telepon ?? '' }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   value="1" {{ $admin->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Akun Aktif</label>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
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
