@extends('layouts.app')
@section('title', 'Evaluasi SPK')
@push('start')
    @vite(['resources/js/admin/spk/index.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        <div class="card flex-row w-100">
            <div style="width: 400px;"><canvas id="bobot-chart"></canvas></div>
            <div class="flex-column d-flex w-100">
                <div class="d-flex flex-column gap-1 w-100 p-3 bobot_input">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h5 class="fw-bold">Bobot Parameter</h5>
                        <a href="{{ route('admin.evaluasi.spk.detail') }}"
                            class="btn btn-primary mx-2 d-flex flex-row gap-2 align-items-center">
                            <i class="fas fa-external-link-alt"></i> Edit
                        </a>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_ipk" class="input-group-text" style="min-width: 112px;">IPK</label>
                        <input type="number" class="form-control w-100" id="bobot_ipk" name="bobot_ipk"
                            value="{{ $spk['IPK'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_skill" class="input-group-text" style="min-width: 112px;">Skill</label>
                        <input type="number" class="form-control w-100" id="bobot_skill" name="bobot_skill"
                            value="{{ $spk['keahlian'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_pengalaman" class="input-group-text" style="min-width: 112px;">Pengalaman</label>
                        <input type="number" class="form-control w-100" id="bobot_pengalaman" name="bobot_pengalaman"
                            value="{{ $spk['pengalaman'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_jarak" class="input-group-text" style="min-width: 112px;">Jarak</label>
                        <input type="number" class="form-control w-100" id="bobot_jarak" name="bobot_jarak"
                            value="{{ $spk['jarak'] }}" step="0.0001" readonly disabled>
                    </div>
                    <div class="input-group  flex-nowrap">
                        <label for="bobot_posisi" class="input-group-text" style="min-width: 112px;">Posisi</label>
                        <input type="number" class="form-control w-100" id="bobot_posisi" name="bobot_posisi"
                            value="{{ $spk['posisi'] }}" step="0.0001" readonly disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-column gap-2 mt-4">
            <div class="flex-row d-flex w-100 justify-content-between align-items-center p-3">
                <h5 class="fw-bold">Feedback SPK dari Mahasiswa</h5>
                <a href="{{ route('admin.evaluasi.spk.feedback') }}"
                    class="btn btn-outline-primary mx-2 d-flex flex-row gap-2 align-items-center">
                    Lihat Kotak Feedback <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="flex-row w-100 p-3 d-flex gap-5">
                <div class="d-flex flex-column gap-2 align-items-center">
                    <h6 class="fw-bold">Persentase Kepuasan</h6>
                    <div style="width: 300px;"><canvas id="percent-puas-chart"></canvas></div>
                </div>
                <table class="table table-borderless mt-5">
                    <tbody>
                        <tr>
                            <td style="width: 86px;">
                                <span class="fw-bold">Rating 5</span>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $feedbackRating[4] == 0 ? 0 : ($feedbackRating[4] / $feedbackRatingTotal) * 100 }}%"
                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 86px;">
                                <span class="fw-bold">Rating 4</span>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $feedbackRating[3] == 0 ? 0 : ($feedbackRating[3] / $feedbackRatingTotal) * 100 }}%"
                                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 86px;">
                                <span class="fw-bold">Rating 3</span>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $feedbackRating[2] == 0 ? 0 : ($feedbackRating[2] / $feedbackRatingTotal) * 100 }}%"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 86px;">
                                <span class="fw-bold">Rating 2</span>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $feedbackRating[1] == 0 ? 0 : ($feedbackRating[1] / $feedbackRatingTotal) * 100 }}%"
                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 86px;">
                                <span class="fw-bold">Rating 1</span>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ $feedbackRating[0] == 0 ? 0 : ($feedbackRating[0] / $feedbackRatingTotal) * 100 }}%"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const run = () => {
            percentageChart.setData(
                {{ $feedbackRatingTotal == 0
                    ? 0
                    : ((1 * $feedbackRating[0] +
                            2 * $feedbackRating[1] +
                            3 * $feedbackRating[2] +
                            4 * $feedbackRating[3] +
                            5 * $feedbackRating[4]) /
                            ($feedbackRatingTotal * 5)) *
                        100 }}
            );

        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
