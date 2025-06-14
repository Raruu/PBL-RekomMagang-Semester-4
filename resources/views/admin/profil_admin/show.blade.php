<div class="row">
    <!-- Foto Profil -->
    <div class="col-md-4 d-flex flex-column align-items-center">
        <div class="position-relative mb-3">
            @if($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                <img src="{{ $admin->profilAdmin->foto_profil }}" alt="Foto Profil"
                    class="img-thumbnail rounded-circle shadow"
                    style="width: 180px; height: 180px; object-fit: cover;">
            @else
                <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile"
                    class="img-thumbnail rounded-circle shadow"
                    style="width: 180px; height: 180px; object-fit: cover;">
            @endif
        </div>
        <h4 class="text-center mb-0">{{ $admin->profilAdmin->nama ?? 'Tidak tersedia' }}</h4>
        <small class="text-muted">Administrator</small>
    </div>
    <!-- Detail Informasi -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-user me-1"></i>Username
                        </label>
                        <div class="fw-semibold">{{ $admin->username }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-envelope me-1"></i>Email
                        </label>
                        <div class="fw-semibold">{{ $admin->email }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-phone me-1"></i>Nomor Telepon
                        </label>
                        <div class="fw-semibold">{{ $admin->profilAdmin->nomor_telepon ?? 'Tidak tersedia' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-user-check me-1"></i>Status
                        </label>
                        <div class="fw-semibold">
                            <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-calendar-plus me-1"></i>Dibuat Pada
                        </label>
                        <div class="fw-semibold">{{ $admin->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-calendar-check me-1"></i>Diupdate Pada
                        </label>
                        <div class="fw-semibold">{{ $admin->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>