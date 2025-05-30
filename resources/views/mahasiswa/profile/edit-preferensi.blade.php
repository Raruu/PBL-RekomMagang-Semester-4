<div class="d-flex flex-column gap-3 flex-fill">
    <h4 class="fw-bold mb-0">Preferensi Magang</h4>
    <div class="card w-100">
        <div class="card-body">
            <div class="mb-3">
                <h5 class="card-title">Lokasi</h5>
                <input type="number" class="d-none" name="location_latitude" id="location_latitude" readonly
                    value="{{ $user->preferensiMahasiswa->lokasi->latitude }}">
                <input type="number" class="d-none" name="location_longitude" id="location_longitude" readonly
                    value="{{ $user->preferensiMahasiswa->lokasi->longitude }}">
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ $user->preferensiMahasiswa->lokasi->alamat }}"
                        name="lokasi_alamat" id="lokasi_alamat" required readonly>
                    <button class="btn btn-outline-secondary d-flex justify-content-center align-items-center"
                        type="button" onClick="preferensiPickLocation()">
                        <svg class="nav-icon" style="width: 20px; height: 20px;">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-location-pin') }}">
                            </use>
                        </svg>
                    </button>
                </div>
                <div id="error-lokasi_alamat" class="text-danger"></div>
            </div>
            <div class="mb-3">
                <h5 class="card-title">Posisi</h5>
                <input type="text" class="form-control" value="{{ $user->preferensiMahasiswa->posisi_preferensi }}"
                    name="posisi_preferensi" id="posisi_preferensi" required>
                <div id="error-posisi_preferensi" class="text-danger"></div>
            </div>
            <div class="mb-3">
                <h5 class="card-title">Tipe Kerja</h5>
                <select class="form-select" name="tipe_kerja_preferensi" id="tipe_kerja_preferensi" required>
                    @foreach ($tipe_kerja_preferensi as $value => $label)
                        <option value="{{ $value }}"
                            {{ $user->preferensiMahasiswa->tipe_kerja_preferensi == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <script>
        const preferensiPickLocation = () => {
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
                {{ empty($user->preferensiMahasiswa->lokasi->longitude) ? '106.84513' : $user->preferensiMahasiswa->lokasi->longitude }};
            const latitude = document.getElementById('location_latitude');
            latitude.value =
                {{ empty($user->preferensiMahasiswa->lokasi->latitude) ? '-6.21462' : $user->preferensiMahasiswa->lokasi->latitude }};
        });
    </script>
</div>
