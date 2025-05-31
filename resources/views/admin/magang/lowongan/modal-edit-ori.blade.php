<!-- Modal Edit Lowongan -->
<div class="modal fade" id="editLowonganModal" tabindex="-1" aria-labelledby="editLowonganModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLowonganModalLabel">Edit Lowongan Magang</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editModalContent">
                <form id="editLowonganForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Informasi Dasar -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Informasi Dasar</h6>

                            <div class="mb-3">
                                <label for="edit_perusahaan_id" class="form-label">Perusahaan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_perusahaan_id" name="perusahaan_id" required>
                                    <option value="">Pilih Perusahaan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_lokasi_id" class="form-label">Lokasi <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_lokasi_id" name="lokasi_id" required>
                                    <option value="">Pilih Lokasi</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_judul_lowongan" class="form-label">Judul Lowongan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_judul_lowongan" name="judul_lowongan"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_judul_posisi" class="form-label">Judul Posisi <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_judul_posisi" name="judul_posisi"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4"
                                    required></textarea>
                            </div>
                        </div>

                        <!-- Detail Lowongan -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Detail Lowongan</h6>

                            <div class="mb-3">
                                <label for="edit_gaji" class="form-label">Gaji</label>
                                <input type="number" class="form-control" id="edit_gaji" name="gaji" min="0">
                            </div>

                            <div class="mb-3">
                                <label for="edit_kuota" class="form-label">Kuota <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="edit_kuota" name="kuota" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_tipe_kerja_lowongan" class="form-label">Tipe Kerja <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="edit_tipe_kerja_lowongan" name="tipe_kerja_lowongan"
                                    required>
                                    <option value="onsite">Onsite</option>
                                    <option value="remote">Remote</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_batas_pendaftaran" class="form-label">Batas Pendaftaran <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_batas_pendaftaran"
                                    name="batas_pendaftaran" required>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active"
                                        value="1">
                                    <label class="form-check-label" for="edit_is_active">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Persyaratan -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Persyaratan</h6>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_minimum_ipk" class="form-label">Minimum IPK</label>
                                <input type="number" class="form-control" id="edit_minimum_ipk" name="minimum_ipk"
                                    step="0.01" min="0" max="4">
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_pengalaman"
                                        name="pengalaman" value="1">
                                    <label class="form-check-label" for="edit_pengalaman">
                                        Memerlukan Pengalaman
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_deskripsi_persyaratan" class="form-label">Deskripsi Persyaratan</label>
                                <textarea class="form-control" id="edit_deskripsi_persyaratan"
                                    name="deskripsi_persyaratan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Keahlian -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Keahlian yang Dibutuhkan</h6>
                            <div id="editKeahlianContainer">
                                <!-- Keahlian items will be populated here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="editAddKeahlianBtn">
                                <i class="fas fa-plus"></i> Tambah Keahlian
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="editSimpanBtn">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.lowonganData !== 'undefined') {
            populateEditForm();
        }

        // Add keahlian button
        $(document).on('click', '#editAddKeahlianBtn', function() {
            addEditKeahlianRow();
        });

        // Remove keahlian button
        $(document).on('click', '.edit-remove-keahlian', function() {
            $(this).closest('.keahlian-row').remove();
        });

        // Submit form
        $(document).on('click', '#editSimpanBtn', function() {
            submitEditForm();
        });
    });

    function populateEditForm() {
        const lowongan = window.lowonganData;

        // Populate basic fields
        $('#edit_judul_lowongan').val(lowongan.judul_lowongan);
        $('#edit_judul_posisi').val(lowongan.judul_posisi);
        $('#edit_deskripsi').val(lowongan.deskripsi);
        $('#edit_gaji').val(lowongan.gaji);
        $('#edit_kuota').val(lowongan.kuota);
        $('#edit_tipe_kerja_lowongan').val(lowongan.tipe_kerja_lowongan);
        $('#edit_batas_pendaftaran').val(lowongan.batas_pendaftaran);
        $('#edit_is_active').prop('checked', lowongan.is_active);

        // Populate dropdowns
        populateEditDropdowns();

        // Set selected values
        $('#edit_perusahaan_id').val(lowongan.perusahaan_id).trigger('change');
        $('#edit_lokasi_id').val(lowongan.lokasi_id).trigger('change');

        // Populate persyaratan
        if (lowongan.persyaratan_magang) {
            $('#edit_minimum_ipk').val(lowongan.persyaratan_magang.minimum_ipk);
            $('#edit_deskripsi_persyaratan').val(lowongan.persyaratan_magang.deskripsi_persyaratan);
            $('#edit_pengalaman').prop('checked', lowongan.persyaratan_magang.pengalaman);
        }

        // Populate keahlian
        populateEditKeahlian();
    }

    function populateEditDropdowns() {
        // Perusahaan dropdown
        let perusahaanOptions = '<option value="">Pilih Perusahaan</option>';
        window.perusahaanList.forEach(function(perusahaan) {
            perusahaanOptions += `<option value="${perusahaan.perusahaan_id}">${perusahaan.nama_perusahaan}</option>`;
        });
        $('#edit_perusahaan_id').html(perusahaanOptions);

        // Lokasi dropdown
        let lokasiOptions = '<option value="">Pilih Lokasi</option>';
        window.lokasiList.forEach(function(lokasi) {
            lokasiOptions += `<option value="${lokasi.lokasi_id}">${lokasi.alamat}</option>`;
        });
        $('#edit_lokasi_id').html(lokasiOptions);
    }

    function populateEditKeahlian() {
        const container = $('#editKeahlianContainer');
        container.empty();

        if (window.lowonganData.keahlian_lowongan && window.lowonganData.keahlian_lowongan.length > 0) {
            window.lowonganData.keahlian_lowongan.forEach(function(item) {
                addEditKeahlianRow(item.keahlian.keahlian_id, item.kemampuan_minimum);
            });
        } else {
            addEditKeahlianRow();
        }
    }

    function addEditKeahlianRow(selectedKeahlianId = '', selectedTingkat = '') {
        const container = $('#editKeahlianContainer');

        let keahlianOptions = '<option value="">Pilih Keahlian</option>';
        window.keahlianList.forEach(function(keahlian) {
            const selected = keahlian.keahlian_id == selectedKeahlianId ? 'selected' : '';
            keahlianOptions += `<option value="${keahlian.keahlian_id}" ${selected}>${keahlian.nama_keahlian}</option>`;
        });

        const tingkatOptions = `
        <option value="pemula" ${selectedTingkat === 'pemula' ? 'selected' : ''}>Pemula</option>
        <option value="menengah" ${selectedTingkat === 'menengah' ? 'selected' : ''}>Menengah</option>
        <option value="mahir" ${selectedTingkat === 'mahir' ? 'selected' : ''}>Mahir</option>
        <option value="ahli" ${selectedTingkat === 'ahli' ? 'selected' : ''}>Ahli</option>
    `;

        const row = `
        <div class="row keahlian-row mb-2">
            <div class="col-md-5">
                <select class="form-select" name="keahlian[id][]" required>
                    ${keahlianOptions}
                </select>
            </div>
            <div class="col-md-5">
                <select class="form-select" name="keahlian[tingkat][]" required>
                    ${tingkatOptions}
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm edit-remove-keahlian">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

        container.append(row);
    }

    function submitEditForm() {
        const formData = new FormData();
        const form = document.getElementById('editLowonganForm');

        // Get basic form data
        const basicData = new FormData(form);
        for (let [key, value] of basicData.entries()) {
            formData.append(key, value);
        }

        // Handle keahlian data specially
        const keahlianIds = $('select[name="keahlian[id][]"]').map(function() {
            return $(this).val();
        }).get();
        const keahlianTingkat = $('select[name="keahlian[tingkat][]"]').map(function() {
            return $(this).val();
        }).get();

        keahlianIds.forEach(function(id, index) {
            if (id && keahlianTingkat[index]) {
                formData.append(`keahlian[${index}][id]`, id);
                formData.append(`keahlian[${index}][tingkat]`, keahlianTingkat[index]);
            }
        });

        // Show loading
        $('#editSimpanBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            url: `/admin/magang/lowongan/${window.currentLowonganId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editLowonganModal').modal('hide');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Reload datatable
                    if (typeof window.dataTable !== 'undefined') {
                        window.dataTable.ajax.reload();
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                alert(errorMessage);
            },
            complete: function() {
                $('#editSimpanBtn').prop('disabled', false).html('Simpan Perubahan');
            }
        });
    }
</script>