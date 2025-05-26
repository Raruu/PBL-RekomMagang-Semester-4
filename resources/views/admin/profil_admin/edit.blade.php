{{-- resources/views/admin/profil_admin/edit.blade.php --}}
<form id="formEditAdmin" action="{{ url('/admin/pengguna/admin/' . $admin->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Photo Section -->
            <div class="col-md-4">
                <div class="text-center mb-4">
                    @if($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                        <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}" 
                             alt="Foto Profil" class="img-thumbnail rounded-circle" id="previewImage"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('imgs/profile_placeholder.jpg') }}" alt="Default Profile"
                             class="img-thumbnail rounded-circle" id="previewImage"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    <div class="mt-3">
                        <label for="foto_profil" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                               accept="image/jpeg,image/png,image/jpg">
                        <div class="form-text">Format: JPEG, PNG, JPG. Maksimal 2MB.</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Fields Section -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="{{ $admin->username }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" 
                           value="{{ $admin->profilAdmin->nama ?? '' }}">
                </div>
                
                <div class="mb-3">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" 
                           value="{{ $admin->profilAdmin->nomor_telepon ?? '' }}">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               value="1" {{ $admin->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Akun Aktif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
            <i class="fas fa-times"></i> Batal
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
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