<div class="d-flex flex-column gap-3 flex-fill">
    <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex flex-row gap-3 flex-fill">
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Email</h5>
                        <input type="email" class="form-control" value="{{ $user->user->email }}" name="email"
                            id="email" required>
                        <div id="error-email" class="text-danger"></div>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Nomor Telepon</h5>
                        <input type="number" class="form-control" value="{{ $user->nomor_telepon }}"
                            name="nomor_telepon" id="nomor_telepon" required>
                        <div id="error-nomor_telepon" class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
