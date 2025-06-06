<div class="modal fade" id="modalEditPeriode" tabindex="-1" aria-labelledby="modalEditPeriodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg rounded-6 border-0">
            <div class="modal-header bg-primary text-white">
                <div class="d-flex align-items-center w-100">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="icon-wrapper me-3">
                            <i class="fas fa-calendar-alt fa-lg text-primary"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="modalEditPeriodeLabel">
                                Edit Periode Lowongan
                            </h5>
                            <small class="text-white-50">Kelola periode waktu lowongan kerja</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white px-3" data-coreui-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>

            <form id="formEditPeriode">
                <div class="modal-body p-4">
                    <input type="hidden" id="editLowonganId" name="lowongan_id">

                    <!-- Info Lowongan Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <div class="card border rounded">
                                <div class="card-body p-3 info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        <h6 class="mb-0 fw-bold">Judul Lowongan</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div id="editJudulLowongan"
                                                class="fs-5 fw-bold text-primary d-flex align-items-center">
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border rounded">
                                <div class="card-body p-3 info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-building text-success me-2"></i>
                                        <h6 class="mb-0 fw-bold">Perusahaan</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="col-md-4">
                                                <div id="editPerusahaan" class="d-flex align-items-center">
                                                    <span id="editPerusahaanBadge"
                                                        class="badge bg-primary-subtle text-primary px-3 py-2 mt-2 rounded-pill"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Periode Section -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3 position-relative">
                                <div
                                    class="position-absolute top-0 start-0 h-100 border-start border-4 border-primary rounded-start">
                                </div>
                                <div class="d-flex align-items-center justify-content-between ps-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-calendar-week me-2 text-primary"></i>
                                            Atur Periode Lowongan
                                        </h6>
                                        <small class="text-muted">Tentukan waktu mulai dan berakhirnya lowongan</small>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm d-flex align-items-center shadow-sm"
                                        id="btnKosongkanTanggal">
                                        <i class="fas fa-eraser me-2"></i>
                                        Kosongkan Tanggal
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="editTanggalMulai" class="form-label fw-semibold d-flex align-items-center">
                                <i class="fas fa-play text-success me-2"></i>
                                Tanggal Mulai
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-success-subtle border-success-subtle">
                                    <i class="fas fa-calendar-day text-success"></i>
                                </span>
                                <input type="date" class="form-control border-success-subtle" id="editTanggalMulai"
                                    name="tanggal_mulai">
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih tanggal mulai periode lowongan
                            </div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="editTanggalSelesai" class="form-label fw-semibold d-flex align-items-center">
                                <i class="fas fa-stop text-danger me-2"></i>
                                Tanggal Selesai
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-danger-subtle border-danger-subtle">
                                    <i class="fas fa-calendar-check text-danger"></i>
                                </span>
                                <input type="date" class="form-control border-danger-subtle" id="editTanggalSelesai"
                                    name="tanggal_selesai">
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Pilih tanggal berakhir periode lowongan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 rounded-bottom-4 p-4">
                    <div class="d-flex w-100 gap-2 border-1">
                        <button type="button"
                            class="btn btn-danger border flex-fill d-flex align-items-center justify-content-center"
                            data-coreui-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary flex-fill d-flex align-items-center justify-content-center fw-semibold shadow-sm">
                            <i class="fas fa-save me-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('styles')
    @vite(['resources/css/periode/edit.css'])
@endpush
@push('end')
    <script>
        let modalEditPeriode;
        document.addEventListener('DOMContentLoaded', function () {
            modalEditPeriode = new coreui.Modal(document.getElementById('modalEditPeriode'));

            $(document).on('click', '.btn-edit-periode', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/admin/magang/periode/${id}/edit`, function (res) {
                    if (res.success) {
                        $('#editLowonganId').val(id);
                        $('#editTanggalMulai').val(res.data.tanggal_mulai || '');
                        $('#editTanggalSelesai').val(res.data.tanggal_selesai || '');
                        $('#editJudulLowongan').text(res.data.judul_lowongan || '-');
                        $('#editPerusahaanBadge').text(res.data.perusahaan || '-');
                        modalEditPeriode.show();
                    } else {
                        Swal.fire('Gagal', 'Gagal memuat data lowongan', 'error');
                    }
                }).fail(function () {
                    Swal.fire('Gagal', 'Gagal memuat data lowongan', 'error');
                });
            });

            $('#btnKosongkanTanggal').on('click', function () {
                $('#editTanggalMulai').val('');
                $('#editTanggalSelesai').val('');
            });

            $('#formEditPeriode').on('submit', function (e) {
                e.preventDefault();
                const id = $('#editLowonganId').val();
                const tanggalMulai = $('#editTanggalMulai').val();
                const tanggalSelesai = $('#editTanggalSelesai').val();
                const today = new Date().toISOString().split('T')[0];

                if ((tanggalMulai && tanggalMulai < today) || (tanggalSelesai && tanggalSelesai < today)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tanggal Tidak Valid',
                        text: 'Tanggal mulai dan tanggal selesai tidak boleh sebelum hari ini.',
                    });
                    return;
                } else if (tanggalMulai && tanggalSelesai && tanggalSelesai < tanggalMulai) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tanggal Tidak Valid',
                        text: 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.',
                    });
                    return;
                }
                
                const data = {
                    tanggal_mulai: tanggalMulai,
                    tanggal_selesai: tanggalSelesai,
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                };
                $.ajax({
                    url: `/admin/magang/periode/${id}`,
                    type: 'POST',
                    data: data,
                    success: function (res) {
                        modalEditPeriode.hide();
                        $('#periodeTableBelum').DataTable().ajax.reload(null, false);
                        $('#periodeTableSudah').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message || 'Periode berhasil diperbarui',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        let msg = 'Terjadi kesalahan';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(arr => arr.join(', ')).join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: msg
                        });
                    }
                });
            });
        });
    </script>
@endpush