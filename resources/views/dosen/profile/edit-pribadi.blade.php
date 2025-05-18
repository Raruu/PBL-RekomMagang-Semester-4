<div class="d-flex flex-column gap-3 flex-fill">
    <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex flex-column gap-3 flex-fill">
                
                <!-- Email -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Email</h5>
                        <input type="email" class="form-control"
                            value="{{ optional($user)->user->email }}" name="email" id="email" required>
                        <div id="error-email" class="text-danger"></div>
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Nomor Telepon</h5>
                        <input type="number" class="form-control"
                            value="{{ optional($user)->nomor_telepon }}"
                            name="nomor_telepon" id="nomor_telepon" required>
                        <div id="error-nomor_telepon" class="text-danger"></div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Alamat</h5>
                        <input type="text" class="form-control"
                            value="{{ optional(optional($user)->lokasi)->alamat }}"
                            name="alamat" id="alamat" required>
                        <div id="error-alamat" class="text-danger"></div>
                    </div>
                </div>

                <!-- Minat Penelitian -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Minat Penelitian</h5>
                        <textarea class="form-control" name="minat_penelitian" id="minat_penelitian" rows="3"
                            required>{{ old('minat_penelitian', optional($user)->minat_penelitian) }}</textarea>
                        <div id="error-minat_penelitian" class="text-danger"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
