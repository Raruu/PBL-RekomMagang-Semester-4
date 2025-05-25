@extends('layouts.app')
@section('title', 'Edit Bobot SPK')
@push('start')
    @vite(['resources/js/admin/spk/edit-bobot.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <h5 class="fw-bold">Edit Bobot SPK</h5>
        <div class="card flex-row w-100">
            <div style="width: 800px;"><canvas id="bobot-chart"></canvas></div>
            <div class="d-flex flex-column gap-1 w-100 p-3 bobot_input">
                <h5 class="fw-bold">Bobot Parameter</h5>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_ipk" class="input-group-text" style="min-width: 112px;">IPK</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_ipk" name="bobot_ipk"
                        min="0.0" max="1.0" value="0.5" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_ipk_text" value="0.5"
                        style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_skill" class="input-group-text" style="min-width: 112px;">Skill</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_skill"
                        name="bobot_skill" min="0.0" max="1.0" value="0.5" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_skill_text" value="0.5"
                        style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_pengalaman" class="input-group-text" style="min-width: 112px;">Pengalaman</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_pengalaman"
                        name="bobot_pengalaman" min="0.0" max="1.0" value="0.5" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_pengalaman_text"
                        value="0.5" style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_jarak" class="input-group-text" style="min-width: 112px;">Jarak</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_jarak"
                        name="bobot_jarak" min="0.0" max="1.0" value="0.5" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_jarak_text" value="0.5"
                        style="max-width: 96px;">
                </div>
                <div class="input-group mb-3 flex-nowrap">
                    <label for="bobot_posisi" class="input-group-text" style="min-width: 112px;">Posisi</label>
                    <input type="range" class="form-range form-control h-auto px-3 w-100" id="bobot_posisi"
                        name="bobot_posisi" min="0.0" max="1.0" value="0.5" step="0.0001">
                    <input type="number" class="form-control w-25" placeholder="x" id="bobot_posisi_text" value="0.5"
                        style="max-width: 96px;">
                </div>
                <div class="d-flex flex-row gap-2 justify-content-between px-1">
                    <button type="button" class="btn btn-warning" onclick="editBobot.normalize()">
                        <i class="fas fa-redo-alt"></i> Normalisasi
                    </button>
                    <div class="d-flex flex-row gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="btn btn-primary" id="btn-submit">
                            <x-btn-submit-spinner wrapWithButton="false" size="22">
                                <i class="fas fa-save"></i> Simpan
                            </x-btn-submit-spinner>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
