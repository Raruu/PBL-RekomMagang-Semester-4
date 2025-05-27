@extends('layouts.app')
@section('title', 'Statistik')
@push('start')
    @vite(['resources/js/admin/statistik/MagangVsTidak.js'])
@endpush
@section('content')
    <div class="d-flex flex-column  gap-2 pb-4">
        <div class="d-flex flex-row gap-2 w-100">
            <h5 class="fw-bold">Jumlah Mahasiswa Telah Mendapatkan Magang Vs Tidak</h5>
        </div>
        <div class="card flex-column p-3 d-flex gap-3">
            <div class="flex-fill" style="height: 100px"><canvas id="chart-magang-vs-tidak"></canvas></div>
            <div class="card flex-row p-3 gap-3 w-100">
                <div class="input-group">
                    <label class="input-group-text">Start</label>
                    <select class="form-select start_magang-vs-tidak">
                        @for ($i = date('Y'); $i >= 2015; $i--)
                            <option value="{{ $i }}" {{ $i == 2019 ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="input-group">
                    <label class="input-group-text">End</label>
                    <select class="form-select end_magang-vs-tidak">
                        @for ($i = date('Y'); $i >= 2019; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>

    <script>
        const getCanvasHeight = () => Math.floor(window.innerHeight * 0.5);

        const MagangVsTidak = async (start, end) => {
            try {

                const response = await axios.get('{{ route('admin.statistik.get.MagangVsTidak') }}', {
                    params: {
                        start: start,
                        end: end
                    }
                });
                const acc = response.data.acc;
                const mhs = response.data.mhs;
                const labels = Array.from({
                    length: end - start + 1
                }, (_, i) => String(parseInt(start) + i));
                chartMagangVsTidak.setData(labels, Object.values(acc), Object.values(mhs));
                chartMagangVsTidak.chart.canvas.parentElement.style.height = `${getCanvasHeight()}px`;
            } catch (error) {
                console.error(error);
                Swal.fire({
                    title: `Gagal ${error.response.status}`,
                    text: error.response || 'Terjadi kesalahan.',
                    icon: 'error'
                });
            }
        };

        const run = () => {
            const startMagangVsTidak = document.querySelector('.start_magang-vs-tidak');
            const endMagangVsTidak = document.querySelector('.end_magang-vs-tidak');
            startMagangVsTidak.addEventListener('change', () => {
                MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);
            });
            endMagangVsTidak.addEventListener('change', () => {
                MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);
            })
            MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);

            //  ......................
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
