{{-- resources/views/admin/profil_admin/show.blade.php --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            @if($admin->profilAdmin && $admin->profilAdmin->foto_profil)
                <img src="{{ asset('storage/' . $admin->profilAdmin->foto_profil) }}" alt="Foto Profil"
                    class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="{{ asset('imgs/profile_placeholder.jpg') }}" alt="Default Profile"
                    class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-8">
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Username:</strong></div>
                <div class="col-sm-8">{{ $admin->username }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Email:</strong></div>
                <div class="col-sm-8">{{ $admin->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Nama Lengkap:</strong></div>
                <div class="col-sm-8">{{ $admin->profilAdmin->nama ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Nomor Telepon:</strong></div>
                <div class="col-sm-8">{{ $admin->profilAdmin->nomor_telepon ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Status:</strong></div>
                <div class="col-sm-8">
                    @if($admin->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Dibuat:</strong></div>
                <div class="col-sm-8">{{ $admin->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4"><strong>Terakhir Diupdate:</strong></div>
                <div class="col-sm-8">{{ $admin->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>