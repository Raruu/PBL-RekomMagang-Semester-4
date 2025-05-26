<form id="formEditMahasiswa" action="{{ route('admin.mahasiswa.update', $mahasiswa->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-4">
                @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->foto_profil)
                    <img src="{{ asset('storage/' . $mahasiswa->profilMahasiswa->foto_profil) }}" 
                        alt="Foto Profil" class="img-thumbnail rounded-circle" id="previewImage"
                        style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile" id="previewImage"
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
                        <!-- Baris Pertama: NIM dan Nama -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">NIM/Username<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="{{ $mahasiswa->username }}" required>
                                <div class="invalid-feedback" id="username-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="{{ $mahasiswa->profilMahasiswa->nama ?? '' }}" required>
                                <div class="invalid-feedback" id="nama-error"></div>
                            </div>
                        </div>

                        <!-- Baris Kedua: Program Studi dan Email -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="program_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <select class="form-select" id="program_id" name="program_id" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach($programStudi as $program)
                                        <option value="{{ $program->program_id }}" 
                                            {{ ($mahasiswa->profilMahasiswa->program_id ?? '') == $program->program_id ? 'selected' : '' }}>
                                            {{ $program->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="program_id-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $mahasiswa->email }}" required>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                        </div>

                        <!-- Baris Ketiga: Angkatan dan Nomor Telepon -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="number" class="form-control" id="angkatan" name="angkatan"
                                       value="{{ $mahasiswa->profilMahasiswa->angkatan ?? '' }}" min="2000" max="2030">
                                <div class="invalid-feedback" id="angkatan-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="ipk" class="form-label">IPK</label>
                                <input type="number" step="0.01" class="form-control" id="ipk" name="ipk"
                                    value="{{ $mahasiswa->profilMahasiswa->ipk ?? '' }}" min="0" max="4">
                                <div class="invalid-feedback" id="ipk-error"></div>
                            </div>
                        </div>

                        <!-- Baris Keempat: Password -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                <div class="invalid-feedback" id="password_confirmation-error"></div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="lokasi_id" value="{{ $mahasiswa->profilMahasiswa->lokasi_id ?? 1 }}">
                        <input type="hidden" name="alamat" value="{{ $mahasiswa->profilMahasiswa->alamat ?? '' }}">
                    </div>

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-danger" data-coreui-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formEditMahasiswa');
        const submitBtn = document.getElementById('submitBtn');

        // Clear validation errors
        function clearErrors() {
            document.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
        }

        // Show validation errors
        function showErrors(errors) {
            for (const field in errors) {
                const input = document.getElementById(field);
                const errorDiv = document.getElementById(field + '-error');
                
                if (input) input.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = errors[field][0];
            }
        }

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function () {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (password && confirmation && password !== confirmation) {
                this.classList.add('is-invalid');
                document.getElementById('password_confirmation-error').textContent = 'Password tidak cocok';
            } else {
                this.classList.remove('is-invalid');
                document.getElementById('password_confirmation-error').textContent = '';
            }
        });

        // Clear confirmation when password changes
        document.getElementById('password').addEventListener('input', function () {
            const confirmation = document.getElementById('password_confirmation');
            if (!this.value) {
                confirmation.value = '';
                confirmation.classList.remove('is-invalid');
                document.getElementById('password_confirmation-error').textContent = '';
            }
        });

        // Form submission
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Basic validation
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password && password !== passwordConfirmation) {
                Swal.fire('Error!', 'Password dan konfirmasi password tidak cocok', 'error');
                return;
            }

            if (password && password.length < 5) {
                Swal.fire('Error!', 'Password minimal 5 karakter', 'error');
                return;
            }

            clearErrors();
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            // Submit form
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        // Close modal
                        const modal = document.querySelector('#editMahasiswaModal');
                        if (modal) {
                            const modalInstance = coreui.Modal.getInstance(modal);
                            if (modalInstance) modalInstance.hide();
                        }
                        
                        // Reload DataTable
                        if (typeof $ !== 'undefined' && $.fn.DataTable) {
                            $('#mahasiswaTable').DataTable().ajax.reload(null, false);
                        }
                    });
                } else if (data.status === 'validation_error') {
                    showErrors(data.errors);
                    Swal.fire('Error!', 'Silakan perbaiki kesalahan pada form', 'error');
                } else {
                    throw new Error(data.message || 'Gagal menyimpan data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', error.message || 'Terjadi kesalahan sistem', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            });
        });
    });
</script>