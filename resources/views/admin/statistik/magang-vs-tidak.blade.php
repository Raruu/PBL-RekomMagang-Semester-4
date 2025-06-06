<div class="d-flex flex-column gap-2 magang_vs_tidak" id="magang_vs_tidak">
    <div class="d-flex flex-row gap-2 w-100 justify-content-between">
        <h5 class="fw-bold">Jumlah Mahasiswa Telah Mendapatkan Magang Vs Tidak</h5>
        <button type="button" class="btn btn-outline-success export_excel">
            <i class="fas fa-file-excel"></i>
        </button>

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
    const _MagangVsTidak = async (start, end) => {
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
                title: `Gagal!`,
                text: error.response || 'Terjadi kesalahan.',
                icon: 'error'
            });
        }
    };

    const MagangVsTidak = () => {
        const magangVsTidak = document.querySelector('.magang_vs_tidak');
        const startMagangVsTidak = magangVsTidak.querySelector('.start_magang-vs-tidak');
        const endMagangVsTidak = magangVsTidak.querySelector('.end_magang-vs-tidak');
        startMagangVsTidak.addEventListener('change', () => {
            _MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);
        });
        endMagangVsTidak.addEventListener('change', () => {
            _MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);
        })
        magangVsTidak.querySelector('.export_excel').onclick = () => {
            window.open(
                `{{ route('admin.statistik.excel.MagangVsTidak') }}?start=${startMagangVsTidak.value}&end=${endMagangVsTidak.value}`,
                '_blank');
        };
        _MagangVsTidak(startMagangVsTidak.value, endMagangVsTidak.value);
    };
</script>
