@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">Lengkapi Informasi Lowongan</h3>

        <form id="formLanjutan" action="{{ route('admin.magang.lowongan.lanjutan.store', $lowongan->lowongan_id) }}"
            method="POST">
            @csrf
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white fw-bolder">Persyaratan Magang</div>
                        <div class="card-body">
                            <div class="form-group mb-2">
                                <label class="fw-bold" for="minimum_ipk">Minimum IPK</label>
                                <input type="number" name="minimum_ipk" step="0.01" min="0" max="4" class="form-control"
                                    required>
                            </div>
                            <div class="form-check mb-3">
                                <label class="form-check-label fw-bold" for="pengalaman">Butuh Pengalaman</label>
                                <input type="checkbox" name="pengalaman" class="form-check-input" id="pengalaman" value="1">
                            </div>
                            <div class="form-group">
                                <label class="fw-bold" for="deskripsi_persyaratan">Deskripsi Tambahan</label>
                                <textarea name="deskripsi_persyaratan" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white fw-bolder">Keahlian Diperlukan</div>
                        <div class="card-body">
                            <div id="keahlianContainer">
                                <div class="row keahlian-item mb-3">
                                    <div class="col-8">
                                        <label class="fw-bold">Keahlian</label>
                                        <select name="keahlian[0][id]" class="form-control" required>
                                            <option value="">-- Pilih Keahlian --</option>
                                            @foreach ($keahlianList ?? [] as $keahlian)
                                                <option value="{{ $keahlian->keahlian_id }}">{{ $keahlian->nama_keahlian }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="fw-bold">Tingkat</label>
                                        <select name="keahlian[0][tingkat]" class="form-control" required>
                                            <option value="pemula">Pemula</option>
                                            <option value="menengah">Menengah</option>
                                            <option value="mahir">Mahir</option>
                                            <option value="ahli">Ahli</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addKeahlian" class="btn btn-sm btn-secondary mt-2"><i
                                    class="fas fa-plus"></i> Tambah Keahlian</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.magang.lowongan.index') }}" class="btn btn-secondary">Lewati</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('end')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('keahlianContainer');
            let index = 1;

            document.getElementById('addKeahlian').addEventListener('click', function () {
                const item = document.querySelector('.keahlian-item').cloneNode(true);

                // Reset nilai dropdown
                const selectKeahlian = item.querySelector('select[name^="keahlian"]');
                const selectTingkat = item.querySelectorAll('select')[1];

                selectKeahlian.name = `keahlian[${index}][id]`;
                selectKeahlian.value = '';

                selectTingkat.name = `keahlian[${index}][tingkat]`;
                selectTingkat.value = '';

                container.appendChild(item);
                index++;
            });
        });
    </script>
@endpush