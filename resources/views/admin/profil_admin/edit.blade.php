<form id="formEditAdmin" action="{{ url('/admin/pengguna/admin/' . $admin->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4">
                @if($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                    <img src="{{ $admin->profilAdmin->foto_profil }}" alt="Foto Profil" class="img-thumbnail rounded-circle"
                        id="previewImage" style="width: 150px; height: 150px; object-fit: cover;">
                @else 
                    <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile"
                        class="img-thumbnail rounded-circle" id="previewImage"
                        style="width: 150px; height: 150px; object-fit: cover;">
                @endif
        
                <div class="mt-3 d-flex flex-column align-items-center">
                    <label for="profile_picture" class="form-label">Ubah Foto Profil</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture"
                        accept="image/jpeg,image/png,image/jpg,image/webp" style="width: 250px; cursor: pointer;">
                    <div class="form-text text-center">Format: JPEG, PNG, JPG, WEBP. Maksimal 2MB.</div>
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
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <!-- Account Status (Modern Switch) -->
                        <div class="d-flex justify-content-center my-4">
                            <div class="form-switch position-relative" style="display: flex; align-items: center; gap: 1rem;">
                                <input type="checkbox" class="form-check-input custom-switch" role="switch" id="is_active" 
                                       name="is_active" value="1" {{ $admin->is_active ? 'checked' : '' }}
                                       @if($admin->user_id == Auth::user()->user_id) disabled @endif
                                       style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                <span class="switch-label fw-semibold ms-2 {{ $admin->is_active ? 'active' : 'inactive' }}" id="statusLabel" style="font-size: 1.1rem;">
                                    @if($admin->is_active)
                                        <i class="fas fa-check-circle me-2 text-success"></i>Akun Aktif
                                    @else
                                        <i class="fas fa-times-circle me-2 text-danger"></i>Akun Nonaktif
                                    @endif
                                </span>
                                @if($admin->user_id == Auth::user()->user_id)
                                    <span class="ms-2 text-muted" style="font-size:0.95rem;">(Tidak dapat mengubah status akun sendiri)</span>
                                @endif
                            </div>
                        </div>
                    </div>  

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahanz
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .custom-switch:checked {
        background-color: #198754 !important;
        border-color: #198754 !important;
    }
    .switch-label.active {
        color: #198754;
    }
    .switch-label.inactive {
        color: #dc3545;
    }
</style>

<script>
    document.getElementById('profile_picture').addEventListener('change', function(e) {
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

    document.getElementById('is_active').addEventListener('change', function () {
        const label = document.getElementById('statusLabel');
        if (this.checked) {
            label.innerHTML = '<i class="fas fa-check-circle me-2 text-success"></i>Akun Aktif';
            label.classList.remove('inactive');
            label.classList.add('active');
        } else {
            label.innerHTML = '<i class="fas fa-times-circle me-2 text-danger"></i>Akun Nonaktif';
            label.classList.remove('active');
            label.classList.add('inactive');
        }
    });
</script>
