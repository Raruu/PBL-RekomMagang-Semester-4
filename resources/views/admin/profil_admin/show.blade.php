<div class="row">
    <div class="col-md-4 text-center mb-4">
        @if ($admin->profilAdmin && $admin->profilAdmin->foto_profil)
            <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}" alt="Foto Profil"
                class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @else
            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle mx-auto"
                style="width: 150px; height: 150px; overflow: hidden;">
                <img src="{{ asset('imgs/profile_placeholder.jpg') }}" alt="Placeholder"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        @endif
        <div class="mt-1">
            <small class="text-muted">Foto Profil</small>
        </div>
    </div>

    <div class="col-md-8">
        <h4 class="mb-3">{{ $admin->profilAdmin->nama ?? 'Tidak tersedia' }}</h4>

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%">Username</th>
                    <td>{{ $admin->username }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $admin->email }}</td>
                </tr>
                <tr>
                    <th>Nomor Telepon</th>
                    <td>{{ $admin->profilAdmin->nomor_telepon ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                            {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>{{ $admin->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Diupdate</th>
                    <td>{{ $admin->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>