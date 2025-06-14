<div class="row g-4">
    <!-- Header with Profile Photo and Name -->
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="me-4">
                @if($dosen->profilDosen && $dosen->profilDosen->foto_profil)
                    <img src="{{ asset($dosen->profilDosen->foto_profil) }}?{{ now() }}" alt="Foto Profil"
                        class="img-thumbnail rounded-circle shadow"
                        style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <img src="{{ asset('imgs/profile_placeholder.webp') }}" alt="Default Profile"
                        class="img-thumbnail rounded-circle shadow"
                        style="width: 120px; height: 120px; object-fit: cover;">
                @endif
            </div>
            <div>
                <h2 class="fw-bold mb-1">{{ $dosen->profilDosen->nama ?? 'Tidak tersedia' }}</h2>
                <div class="d-flex align-items-center">
                    <span class="badge bg-secondary me-2">Dosen</span>
                    <span class="badge bg-{{ $dosen->is_active ? 'success' : 'danger' }}">
                        {{ $dosen->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="col-12">
        <!-- Card Informasi Dasar -->
        <div class="card shadow-sm mb-3 border-1">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-id-card me-2"></i>Informasi Dasar</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-id-badge me-2"></i>NIP</span>
                            <span class="fw-medium">{{ $dosen->username }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-envelope me-2"></i>Email</span>
                            <span class="fw-medium">{{ $dosen->email }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-phone me-2"></i>Telepon</span>
                            <span class="fw-medium">{{ $dosen->profilDosen->nomor_telepon ?? '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-map-marker-alt me-2"></i>Alamat</span>
                            <span class="fw-medium">{{ $dosen->profilDosen->lokasi->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Informasi Akademik -->
        <div class="card shadow-sm mb-3 border-1">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-graduation-cap me-2"></i>Informasi Akademik</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-university me-2"></i>Program Studi</span>
                            <span class="fw-medium">{{ $dosen->profilDosen->programStudi->nama_program ?? 'Tidak tersedia' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-flask me-2"></i>Minat Penelitian</span>
                            <span class="fw-medium">{{ $dosen->profilDosen->minat_penelitian ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Informasi Sistem -->
        <div class="card shadow-sm border-1">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-database me-2"></i>Informasi Sistem</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-calendar-plus me-2"></i>Dibuat Pada</span>
                            <span class="fw-medium">{{ $dosen->created_at ? $dosen->created_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="d-block text-muted small"><i class="fas fa-calendar-check me-2"></i>Diupdate Pada</span>
                            <span class="fw-medium">{{ $dosen->updated_at ? $dosen->updated_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>