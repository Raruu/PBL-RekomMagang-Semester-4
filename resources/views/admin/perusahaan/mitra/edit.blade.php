<div class="card-body">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.perusahaan.update', $perusahaan->perusahaan_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="number" class="d-none" name="location_latitude" id="location_latitude" readonly
                    value="{{ $lokasi->latitude }}">
                <input type="number" class="d-none" name="location_longitude" id="location_longitude" readonly
                    value="{{ $lokasi->longitude }}">
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ $lokasi->alamat }}" name="lokasi_alamat"
                        id="lokasi_alamat" required readonly>
                    <button
                        class="btn btn-outline-secondary d-flex justify-content-center align-items-center btn_pick_location"
                        type="button">
                        <svg class="nav-icon" style="width: 20px; height: 20px;">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                            </use>
                        </svg>
                    </button>
                </div>
                <div id="error-lokasi_alamat" class="text-danger"></div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span
                            class="text-danger">*</span></label>
                    <input type="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                        id="nama_perusahaan" name="nama_perusahaan"
                        value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" required>
                    <div id="error-nama_perusahaan" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="bidang_id" class="form-label">Bidang Industri <span class="text-danger">*</span></label>
                    <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror" required>
                        <option value="" disabled>-- Pilih Bidang Industri --</option>
                        @foreach ($bidangIndustri as $bidang)
                            <option value="{{ $bidang->bidang_id }}"
                                {{ $perusahaan->bidang_id == $bidang->bidang_id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-bidang_id" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="website" class="form-label">Website <span class="text-danger">*</span></label>
                    <input type="website" class="form-control @error('website') is-invalid @enderror" id="website"
                        name="website" value="{{ old('website', $perusahaan->website) }}" required>
                    <div id="error-website" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="kontak_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="kontak_email" class="form-control @error('kontak_email') is-invalid @enderror"
                        id="kontak_email" name="kontak_email"
                        value="{{ old('kontak_email', $perusahaan->kontak_email) }}" required>
                    <div id="error-kontak_email" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="kontak_telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="kontak_telepon" class="form-control @error('kontak_telepon') is-invalid @enderror"
                        id="kontak_telepon" name="kontak_telepon"
                        value="{{ old('kontak_telepon', $perusahaan->kontak_telepon) }}" required>
                    <div id="error-kontak_telepon" class="text-danger"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1"
                            {{ isset($perusahaan) && $perusahaan->is_active == 1 ? 'selected' : '' }}>
                            Aktif</option>
                        <option value="0"
                            {{ isset($perusahaan) && $perusahaan->is_active == 0 ? 'selected' : '' }}>
                            Tidak Aktif</option>
                    </select>
                    <div id="error-is_active" class="text-danger"></div>
                </div>
            </div>
        </div>
    </form>
</div>
