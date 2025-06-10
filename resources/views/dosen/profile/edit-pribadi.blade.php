<div class="d-flex flex-column gap-3 flex-fill">
    <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex flex-column gap-3 flex-fill">

                <!-- Email -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Email <span class="text-danger">*</span></h5>
                        <input type="email" class="form-control" value="{{ optional($user)->user->email }}"
                            name="email" id="email" required>
                        <div id="error-email" class="text-danger"></div>
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Nomor Telepon <span class="text-danger">*</span></h5>
                        <input type="number" class="form-control" value="{{ optional($user)->nomor_telepon }}"
                            name="nomor_telepon" id="nomor_telepon" required>
                        <div id="error-nomor_telepon" class="text-danger"></div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Alamat <span class="text-danger">*</span></h5>
                        <input type="number" class="d-none" name="location_latitude" id="location_latitude" readonly
                            value="{{ $user->lokasi->latitude }}">
                        <input type="number" class="d-none" name="location_longitude" id="location_longitude" readonly
                            value="{{ $user->lokasi->longitude }}">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                value="{{ $user->lokasi->alamat }}" name="lokasi_alamat"
                                id="lokasi_alamat" required readonly>
                            <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center"
                                type="button" onClick="pickLocation()">
                                <svg class="nav-icon" style="width: 20px; height: 20px;">
                                    <use
                                        xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                                    </use>
                                </svg>
                            </button>
                        </div>
                        <div id="error-lokasi_alamat" class="text-danger"></div>
                    </div>
                </div>

                <!-- Minat Penelitian -->
                <div class="flex-fill">
                    <div class="mb-3">
                        <h5 class="card-title">Minat Penelitian <span class="text-danger">*</span></h5>
                        <textarea class="form-control" name="minat_penelitian" id="minat_penelitian" rows="3" required>{{ old('minat_penelitian', optional($user)->minat_penelitian) }}</textarea>
                        <div id="error-minat_penelitian" class="text-danger"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    const pickLocation = () => {
        const form = document.querySelector('.main_form');
        const longitude = form.querySelector('#location_longitude');
        const latitude = form.querySelector('#location_latitude');
        const alamat = form.querySelector('#lokasi_alamat');
        openLocationPicker((event) => {
            alamat.value = event.locationOutput.address;
            latitude.value = event.locationOutput.lat;
            longitude.value = event.locationOutput.lng;
        }, alamat.value, {
            lat: latitude.value,
            lng: longitude.value
        })
    };

    document.addEventListener('DOMContentLoaded', () => {
        const longitude = document.getElementById('location_longitude');
        longitude.value =
            {{ empty($user->lokasi->longitude) ? '106.84513' : $user->lokasi->longitude }};
        const latitude = document.getElementById('location_latitude');
        latitude.value =
            {{ empty($user->lokasi->latitude) ? '-6.21462' : $user->lokasi->latitude }};
    });
</script>
