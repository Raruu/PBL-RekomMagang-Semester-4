@vite(['resources/js/import/leaflet.js'])
<div class="modal fade" id="location-picker-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ambil Lokasi</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column gap-3">
                <div id="map-view" style="height: calc(80vh - 150px);" class="flex-fill"></div>
                <div class="input-group">
                    <span class="input-group-text" style="width: 100px;" id="basic-addon1">Latitude</span>
                    <input type="number" class="form-control" id="location-latitude" placeholder="Tandai dipeta"
                        aria-label="Latitude" aria-describedby="basic-addon1" readonly>
                </div>
                <div class="input-group">
                    <span class="input-group-text" style="width: 100px;" id="basic-addon1">Longitude</span>
                    <input type="number" class="form-control" id="location-longitude" placeholder="Tandai dipeta"
                        aria-label="Longitude" aria-describedby="basic-addon1" readonly>
                </div>
                <div class="input-group">
                    <span class="input-group-text" style="width: 100px;" id="basic-addon1">Alamat</span>
                    <input type="text" class="form-control" id="address-input" placeholder="Masukkan Alamat"
                        aria-label="Alamat" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" data-coreui-dismiss="modal"
                    id="btn-true">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const openLocationPicker = (callback, address, cordinates) => {
        const updateBtnTrueDisabled = () => {
            btnTrue.disabled = addressInput.value === "";
        }

        const modalElement = document.getElementById('location-picker-modal');

        const addressInput = modalElement.querySelector("#address-input");
        const latitudeInput = modalElement.querySelector("#location-latitude");
        const longitudeInput = modalElement.querySelector("#location-longitude");
        let map;

        const loadMap = () => {
            map = L.map("map-view").setView([-7.645, 112.572], 10);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);
            let marker;

            const updateAddress = (lat, lng) => {
                fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
                    )
                    .then((response) => response.json())
                    .then((data) => {
                        if (data && data.address) {
                            const address = data.address;
                            const city =
                                address.city ||
                                address.town ||
                                address.village ||
                                address.county ||
                                "";
                            var formattedAddress = "";
                            if (address.road) formattedAddress += address.road + ", ";
                            if (address.neighbourhood)
                                formattedAddress += address.neighbourhood + ", ";
                            formattedAddress += city;
                            if (address.postcode) formattedAddress += " " + address.postcode;
                            if (address.country) formattedAddress += ", " + address.country;                           
                            latitudeInput.value = (parseFloat(lat)).toFixed(7);
                            longitudeInput.value = (parseFloat(lng)).toFixed(7);
                            addressInput.value = formattedAddress;
                            marker.bindPopup(formattedAddress).openPopup();
                            updateBtnTrueDisabled();
                        } else {
                            addressInput.innerText = "Alamat tidak ada dilokasi ini min";
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching:", error);
                    });
            }

            const setViewLatLng = (lat, lng) => {
                let latitude = parseFloat(lat);
                let longitude = parseFloat(lng);
                latitudeInput.value = latitude;
                longitudeInput.value = longitude;
                map.setView([latitude, longitude], 15);
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([latitude, longitude]).addTo(map);
                updateAddress(lat, lng);
            }

            const geocodeAddress = (address) => {
                addressInput.value = address;
                fetch(
                        `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(address)}`
                    )
                    .then((response) => response.json())
                    .then((data) => {
                        if (data && data.length > 0) {
                            setViewLatLng(data[0].lat, data[0].lon);
                        }
                    })
                    .catch((error) => {
                        console.error("Error geocoding address:", error);
                    });
            };

            if (typeof cordinates !== 'undefined') {
                setViewLatLng(cordinates.lat, cordinates.lng);
            } else if (typeof address !== 'undefined') {
                geocodeAddress(address);
            }

            const onMapClick = (e) => {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                updateAddress(e.latlng.lat, e.latlng.lng);
            }

            map.on("click", onMapClick);
            addressInput.addEventListener('blur', () => geocodeAddress(addressInput.value));
            return geocodeAddress;
        };

        const btnTrue = modalElement.querySelector("#btn-true");
        let main;
        const modalOpenHandler = (event) => {
            addressInput.value = "";
            latitudeInput.value = "";
            longitudeInput.value = "";
            btnTrue.disabled = true;
            addressInput.addEventListener('input', updateBtnTrueDisabled);
            setTimeout(() => {
                updateBtnTrueDisabled();
            }, 1);
            btnTrue.onclick = () => callback(event);
            main = loadMap();
            modalElement.removeEventListener('shown.coreui.modal', modalOpenHandler);
        };
        modalElement.addEventListener('shown.coreui.modal', modalOpenHandler);

        const modalHideHandler = (event) => {
            modalElement.removeEventListener('hidden.coreui.modal', modalHideHandler);
            if (map) {
                map.remove();
                map = null;
            }
            addressInput.removeEventListener('blur', main.geocodeAddress);
            addressInput.removeEventListener('input', () => updateBtnTrueDisabled);
        };
        modalElement.addEventListener('hidden.coreui.modal', modalHideHandler);

        const modal = new coreui.Modal(modalElement);
        modal.show();
    };
</script>
