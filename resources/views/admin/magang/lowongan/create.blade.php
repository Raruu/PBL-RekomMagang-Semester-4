@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">{{ $page->title }}</h3>

        <form id="formLowongan" action="{{ route('admin.magang.lowongan.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Kolom Kiri: Pilih Perusahaan + Info -->
                <div class="col-md-4">
                    <!-- Pilih Perusahaan -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white fw-bolder">Pilih Perusahaan Mitra</div>
                        <div class="card-body">
                            <div class="form-group">
                                <select name="perusahaan_id" id="perusahaan_id" class="form-control" required>
                                    <option value="">-- Pilih Perusahaan --</option>
                                    @foreach ($perusahaanList as $perusahaan)
                                        <option value="{{ $perusahaan->perusahaan_id }}"
                                            data-nama="{{ $perusahaan->nama_perusahaan }}"
                                            data-industri="{{ $perusahaan->bidang_industri }}"
                                            data-lokasi-id="{{ $perusahaan->lokasi->lokasi_id }}"
                                            data-lokasi-alamat="{{ $perusahaan->lokasi->alamat }}">
                                            {{ $perusahaan->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="lokasi_id" id="lokasi_id">
                            </div>
                        </div>
                    </div>

                    <!-- Info Perusahaan -->
                    <div class="card shadow mb-4 d-none" id="infoPerusahaan">
                        <div class="card-header bg-secondary text-white fw-bolder">
                            Informasi Perusahaan
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold mb-1"><i class="fas fa-id-card" style="color: #17a2b8;"></i> Nama
                                    Perusahaan</label>
                                <div class="border rounded p-2" id="infoNama"></div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold mb-1"><i class="fas fa-industry" style="color: #FFD63A"></i> Bidang
                                    Industri</label>
                                <div class="border rounded p-2" id="infoIndustri"></div>
                            </div>
                            <div>
                                <label class="fw-bold mb-1"><i class="fas fa-map-marker-alt" style="color: #F75A5A;"></i>
                                    Alamat</label>
                                <div class="border rounded p-2" id="infoAlamat"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Data Lowongan -->
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white fw-bolder">Detail Lowongan</div>
                        <div class="card-body">
                            <div class="form-group mb-1">
                                <label class="fw-bold" for="judul_lowongan">Judul Lowongan</label>
                                <input type="text" name="judul_lowongan" class="form-control" required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="fw-bold" for="judul_posisi">Judul Posisi</label>
                                <input type="text" name="judul_posisi" class="form-control" required>
                            </div>
                            <div class="form-group mb-1">
                                <label class="fw-bold" for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="form-group mb-1">
                                <label class="fw-bold" for="gaji">Gaji (opsional)</label>
                                <input type="number" name="gaji" class="form-control">
                            </div>
                            <div class="form-group mb-1">
                                <label class="fw-bold" for="kuota">Kuota</label>
                                <input type="number" name="kuota" class="form-control" required min="1">
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold" for="tipe_kerja_lowongan">Tipe Kerja</label>
                                <select name="tipe_kerja_lowongan" class="form-control" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="remote">Remote</option>
                                    <option value="onsite">Onsite</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label class="fw-bold" for="batas_pendaftaran">Batas Pendaftaran</label>
                                <input type="date" name="batas_pendaftaran" class="form-control" required>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                                <label class="form-check-label fw-bold">Aktifkan Lowongan</label>
                            </div>

                            <div class="mt-3 justify-content-between d-flex gap-2">
                                <a href="{{ route('admin.magang.lowongan.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formLowongan');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(async response => {
                        if (!response.ok) {
                            const errorData = await response.json();
                            let errorMessages = '';
                            for (const key in errorData.errors) {
                                errorMessages += `${errorData.errors[key].join(', ')}\n`;
                            }
                            throw new Error(errorMessages);
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = `/admin/magang/lowongan/${data.lowongan_id}/lanjutan`;
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: error.message,
                        });
                    });

            });

            const perusahaanSelect = document.getElementById('perusahaan_id');
            const lokasiInput = document.getElementById('lokasi_id');

            const infoContainer = document.getElementById('infoPerusahaan');
            const infoNama = document.getElementById('infoNama');
            const infoIndustri = document.getElementById('infoIndustri');
            const infoAlamat = document.getElementById('infoAlamat');

            perusahaanSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    infoNama.textContent = selected.dataset.nama;
                    infoIndustri.textContent = selected.dataset.industri;
                    infoAlamat.textContent = selected.dataset.lokasiAlamat;
                    lokasiInput.value = selected.dataset.lokasiId;
                    infoContainer.classList.remove('d-none');
                } else {
                    lokasiInput.value = '';
                    infoContainer.classList.add('d-none');
                    infoNama.textContent = '';
                    infoIndustri.textContent = '';
                    infoAlamat.textContent = '';
                }
            });
        });
    </script>
@endpush