<div class="container-fluid">
    <div class="row">
        <!-- Profile Picture and Basic Info -->
        <div class="col-md-4">
            <div class="text-center mb-4">
                <div class="profile-img-container">
                    <img src="{{ $mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->foto_profil
    ? asset($mahasiswa->profilMahasiswa->foto_profil)
    : asset('imgs/profile_placeholder.jpg') }}?{{ now() }}" alt="Foto Profil" class="w-100 h-100 object-fit-cover"
                        id="picture-display">
                </div>
                <h5 class="font-weight-bold">{{ $mahasiswa->profilMahasiswa->nama ?? 'Nama tidak tersedia' }}</h5>
                <p class="text-muted">{{ $mahasiswa->profilMahasiswa->nim ?? 'NIM tidak tersedia' }}</p>
            </div>

            <!-- Account Status -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Status Akun</h6>
                    <span class="badge bg-{{ $mahasiswa->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $mahasiswa->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 text-primary">
                        <i class="fas fa-address-book me-2"></i>Informasi Kontak
                    </h5>

                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope fa-fw me-2 text-muted"></i>
                        <div>
                            <small class="text-muted d-block">Email</small>
                            <div class="fw-semibold">{{ $mahasiswa->email }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <i class="fas fa-phone fa-fw me-2 text-muted"></i>
                        <div>
                            <small class="text-muted d-block">Nomor Telepon</small>
                            <div class="fw-semibold">
                                {{ $mahasiswa->profilMahasiswa->nomor_telepon ?? 'Tidak tersedia' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="col-md-8">
            <!-- Informasi Akademik -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="title pb-2">Informasi Akademik</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingNIM" placeholder="NIM"
                                    value="{{ $mahasiswa->profilMahasiswa->nim ?? 'Tidak tersedia' }}" disabled>
                                <label for="floatingNIM">NIM</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingProdi" placeholder="Program Studi"
                                    value="{{ $mahasiswa->profilMahasiswa->programStudi->nama_program ?? 'Tidak tersedia' }}"
                                    disabled>
                                <label for="floatingProdi">Program Studi</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingSemester" placeholder="Semester"
                                    value="{{ $mahasiswa->profilMahasiswa->angkatan ?? 'Tidak tersedia' }}" disabled>
                                <label for="floatingSemester">Angkatan</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingUsername" placeholder="Username"
                                    value="{{ $mahasiswa->profilMahasiswa->ipk ?? 'Tidak tersedia' }}" disabled>
                                <label for="floatingUsername">IPK</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="title pb-2">Informasi Pribadi</h5>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Alamat" id="floatingAlamat" style="height: 100px"
                            disabled>{{ $mahasiswa->profilMahasiswa->lokasi->alamat ?? 'Tidak tersedia' }}</textarea>
                        <label for="floatingAlamat">Alamat</label>
                    </div>
                </div>
            </div>

            <!-- CV File -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->file_cv)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title">Curriculum Vitae</h5>
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
                        <h5 class="title pb-2">Keahlian</h5>

                        @php
                            $tingkatList = [
                                'ahli' => ['label' => 'Ahli', 'color' => '#dc3545'],
                                'mahir' => ['label' => 'Mahir', 'color' => '#fd7e14'],
                                'menengah' => ['label' => 'Menengah', 'color' => '#17a2b8'],
                                'pemula' => ['label' => 'Pemula', 'color' => '#6c757d']
                            ];
                            $grouped = $mahasiswa->profilMahasiswa->keahlianMahasiswa->groupBy('tingkat_kemampuan');
                        @endphp

                        @foreach($tingkatList as $key => $info)
                            @if($grouped->has($key))
                                <div class="card mb-3" style="border-left: 4px solid {{ $info['color'] }};">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2" style="color: {{ $info['color'] }};">{{ $info['label'] }}</h6>
                                        <ul class="mb-0">
                                            @foreach($grouped[$key] as $item)
                                                <li>{{ $item->keahlian->nama_keahlian ?? 'Keahlian' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pengalaman -->
            @if($mahasiswa->profilMahasiswa && $mahasiswa->profilMahasiswa->pengalamanMahasiswa->count() > 0)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="title pb-2">Pengalaman</h5>
                        @foreach($mahasiswa->profilMahasiswa->pengalamanMahasiswa as $pengalaman)
                            <div class="card border-left-primary mb-3" style="border-left: 4px solid #007bff;">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-1">{{ $pengalaman->nama_pengalaman ?? 'Pengalaman' }}</h6>

                                    @if($pengalaman->tipe_pengalaman)
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-briefcase me-1"></i>{{ ucfirst($pengalaman->tipe_pengalaman) }}
                                        </p>
                                    @endif

                                    @if($pengalaman->periode_mulai || $pengalaman->periode_selesai)
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $pengalaman->periode_mulai ? date('M Y', strtotime($pengalaman->periode_mulai)) : 'Tidak diketahui' }}
                                            -
                                            {{ $pengalaman->periode_selesai ? date('M Y', strtotime($pengalaman->periode_selesai)) : 'Sekarang' }}
                                        </p>
                                    @endif
                                    <br>
                                    @if($pengalaman->deskripsi_pengalaman)
                                        <strong>Deskripsi:</strong>
                                        <p class="mb-2">{{ $pengalaman->deskripsi_pengalaman }}</p>
                                    @endif
                                    <br>
                                    @if($pengalaman->pengalamanTagBelongsToMany && $pengalaman->pengalamanTagBelongsToMany->count() > 0)
                                        <div>
                                            <strong>Keahlian Terkait:</strong>
                                            <ul class="mb-0">
                                                @foreach($pengalaman->pengalamanTagBelongsToMany as $tag)
                                                    <li>{{ $tag->nama_keahlian }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
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
                        <h5 class="title pb-2">Preferensi Magang</h5>

                        <div class="card border-left-success mb-3" style="border-left: 4px solid #28a745;">
                            <div class="card-body">
                                @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->posisi_preferensi)
                                    <p class="mb-2">
                                        <i class="fas fa-user-tie me-2 text-success"></i>
                                        <strong>Posisi yang Diharapkan:</strong><br>
                                        {{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->posisi_preferensi }}
                                    </p>
                                @endif

                                @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->tipe_kerja_preferensi)
                                                    <p class="mb-2">
                                                        <i class="fas fa-briefcase me-2 text-success"></i>
                                                        <strong>Tipe Kerja:</strong><br>
                                                        {{
                                    \App\Models\PreferensiMahasiswa::TIPE_KERJA_PREFERENSI[
                                        $mahasiswa->profilMahasiswa->preferensiMahasiswa->tipe_kerja_preferensi
                                    ] ?? $mahasiswa->profilMahasiswa->preferensiMahasiswa->tipe_kerja_preferensi
                                                                                                    }}
                                                    </p>
                                @endif

                                @if($mahasiswa->profilMahasiswa->preferensiMahasiswa->lokasi)
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                        <strong>Lokasi Magang:</strong><br>
                                        {{ $mahasiswa->profilMahasiswa->preferensiMahasiswa->lokasi->alamat ?? 'Tidak tersedia' }}
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Informasi Akun -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="title pb-2">Informasi Akun</h5>

                    <div class="card border-left-info mb-3" style="border-left: 4px solid #17a2b8;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-plus me-2 text-info"></i>
                                        <strong>Akun Dibuat:</strong><br>
                                        {{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y H:i') : 'Tidak tersedia' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p class="mb-0">
                                        <i class="fas fa-sync-alt me-2 text-info"></i>
                                        <strong>Terakhir Diperbarui:</strong><br>
                                        {{ $mahasiswa->updated_at ? $mahasiswa->updated_at->format('d M Y H:i') : 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>