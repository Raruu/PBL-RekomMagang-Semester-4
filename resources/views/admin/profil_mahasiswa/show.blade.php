<div class="container-fluid">
    <div class="row">
        <!-- Profile Picture and Basic Info -->
        <div class="col-md-4">
            <div class="text-center mb-4">
                <div class="profile-img-container">
                    @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->foto_profil)
                        <img src="{{ $mahasiswa->profilMahasiswa->foto_profil }}" alt="Foto Profil" class="img-fluid">
                    @else
                        <img src="/api/placeholder/150/150" alt="Default Profile" class="img-fluid">
                    @endif
                </div>
                <h5 class="font-weight-bold">{{ $mahasiswa->profilMahasiswa->nama ?? 'Nama tidak tersedia' }}</h5>
                <p class="text-muted">{{ $mahasiswa->profilMahasiswa->nim ?? 'NIM tidak tersedia' }}</p>
            </div>

            <!-- Account Status -->
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title">Status Akun</h6>
                    <span class="badge bg-{{ $mahasiswa->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $mahasiswa->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title">Informasi Kontak</h6>
                    <div class="info-row">
                        <small class="info-label">Email:</small>
                        <div class="info-value">{{ $mahasiswa->email }}</div>
                    </div>
                    <div class="info-row">
                        <small class="info-label">Nomor Telepon:</small>
                        <div class="info-value">{{ $mahasiswa->profilMahasiswa->nomor_telepon ?? 'Tidak tersedia' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-md-8">
            <!-- Academic Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="section-title">Informasi Akademik</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">NIM:</small>
                                <div class="info-value">{{ $mahasiswa->profilMahasiswa->nim ?? 'Tidak tersedia' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">Program Studi:</small>
                                <div class="info-value">
                                    {{ $mahasiswa->profilMahasiswa->programStudi->nama_program ?? 'Tidak tersedia' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">Semester:</small>
                                <div class="info-value">{{ $mahasiswa->profilMahasiswa->semester ?? 'Tidak tersedia' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">Username:</small>
                                <div class="info-value">{{ $mahasiswa->username }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="section-title">Informasi Pribadi</h5>
                    <div class="info-row">
                        <small class="info-label">Alamat:</small>
                        <div class="info-value">{{ $mahasiswa->profilMahasiswa->alamat ?? 'Tidak tersedia' }}</div>
                    </div>
                </div>
            </div>

            <!-- CV File -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->file_cv)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">Curriculum Vitae</h5>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <a href="{{ $mahasiswa->profilMahasiswa->file_cv }}" target="_blank"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Download CV
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Keahlian -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->keahlianMahasiswa->count() > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">Keahlian</h5>
                        <div class="row">
                            @foreach($mahasiswa->profilMahasiswa->keahlianMahasiswa as $keahlian)
                                <div class="col-md-6 mb-2">
                                    <div class="info-row">
                                        <small class="info-label">{{ $keahlian->nama_keahlian ?? 'Keahlian' }}:</small>
                                        <div class="info-value">
                                            @if($keahlian->tingkat_keahlian)
                                                <span class="badge bg-primary">{{ $keahlian->tingkat_keahlian }}</span>
                                            @else
                                                Tingkat tidak disebutkan
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pengalaman -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->pengalamanMahasiswa->count() > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">Pengalaman</h5>
                        @foreach($mahasiswa->profilMahasiswa->pengalamanMahasiswa as $pengalaman)
                            <div class="card border-left-primary mb-3" style="border-left: 4px solid #007bff;">
                                <div class="card-body">
                                    <h6 class="font-weight-bold">{{ $pengalaman->judul_pengalaman ?? 'Pengalaman' }}</h6>
                                    @if($pengalaman->perusahaan)
                                        <p class="text-muted mb-1"><i class="fas fa-building me-1"></i>{{ $pengalaman->perusahaan }}
                                        </p>
                                    @endif
                                    @if($pengalaman->tanggal_mulai || $pengalaman->tanggal_selesai)
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $pengalaman->tanggal_mulai ? date('M Y', strtotime($pengalaman->tanggal_mulai)) : 'Tidak diketahui' }}
                                            -
                                            {{ $pengalaman->tanggal_selesai ? date('M Y', strtotime($pengalaman->tanggal_selesai)) : 'Sekarang' }}
                                        </p>
                                    @endif
                                    @if($pengalaman->deskripsi)
                                        <p class="mb-0">{{ $pengalaman->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Preferensi -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->preferensiMahasiswa)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="section-title">Preferensi Magang</h5>
                        <div class="row">
                            @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->jenis_magang)
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <small class="info-label">Jenis Magang:</small>
                                        <div class="info-value">
                                            {{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->jenis_magang }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->lokasi_magang)
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <small class="info-label">Lokasi Magang:</small>
                                        <div class="info-value">
                                            {{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->lokasi_magang }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->durasi_magang)
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <small class="info-label">Durasi Magang:</small>
                                        <div class="info-value">
                                            {{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->durasi_magang }} bulan</div>
                                    </div>
                                </div>
                            @endif
                            @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->waktu_mulai)
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <small class="info-label">Waktu Mulai:</small>
                                        <div class="info-value">
                                            {{ date('M Y', strtotime($mahasiswa->profilMahasiswa->preferensiMahasiswa->waktu_mulai)) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->catatan)
                            <div class="info-row mt-3">
                                <small class="info-label">Catatan Tambahan:</small>
                                <div class="info-value">{{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->catatan }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Account Creation Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="section-title">Informasi Akun</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">Akun Dibuat:</small>
                                <div class="info-value">
                                    {{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y H:i') : 'Tidak tersedia' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <small class="info-label">Terakhir Diperbarui:</small>
                                <div class="info-value">
                                    {{ $mahasiswa->updated_at ? $mahasiswa->updated_at->format('d M Y H:i') : 'Tidak tersedia' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>