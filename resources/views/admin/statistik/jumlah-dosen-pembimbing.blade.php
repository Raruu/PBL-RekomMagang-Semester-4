<div class="d-flex flex-column gap-2 jumlah_dosen_pembimbing">
    <div class="d-flex flex-row gap-2 w-100 justify-content-between">
        <h5 class="fw-bold">Jumlah Dosen Pembimbing Magang {{ \Carbon\Carbon::now()->format('d/m/Y') }} </h5>
        <button type="button" class="btn btn-outline-success export_excel">
            <i class="fas fa-file-excel"></i>
        </button>

    </div>
    <div class="card flex-column p-3 d-flex gap-3">
        <div class="card flex-row p-3 gap-3 w-100">
            <div class="input-group">
                <label class="input-group-text">Program Studi</label>
                <input type="text" class="form-control program_studi" value="{{ implode(', ', $programStudi) }}">
            </div>
        </div>
        <div class="flex-fill" style="height: 100px"><canvas id="chart-jumlah-dosen-pembimbing"></canvas></div>
    </div>
</div>

<script>
    const _JumlahDosenPembimbing = async (programStudi) => {
        try {
            const response = await axios.get('{{ route('admin.statistik.get.JumlahDosenPembimbing') }}', {
                params: {
                    programStudi: programStudi
                }
            });

            const data = response.data.data;

            chartJumlahDosenPembimbing.setData(Object.keys(data), Object.values(data));
            chartJumlahDosenPembimbing.chart.canvas.parentElement.style.height = `${getCanvasHeight()}px`;
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: `Gagal ${error.response.status}`,
                text: error.response || 'Terjadi kesalahan.',
                icon: 'error'
            });
        }
    };

    const JumlahDosenPembimbing = () => {
        const jumlahDosenPembimbing = document.querySelector('.jumlah_dosen_pembimbing');
        const tagify = new Tagify(jumlahDosenPembimbing.querySelector('.program_studi'), {
            whitelist: @json($programStudi),
            dropdown: {
                position: "input",
                maxItems: Infinity,
                enabled: 0,
            },
            templates: {
                dropdownItemNoMatch() {
                    return `Nothing Found`;
                }
            },
            enforceWhitelist: true,
        });

        tagify.on('change', e => {
            _JumlahDosenPembimbing(tagify.value.map(tag => tag.value));
        });

        jumlahDosenPembimbing.querySelector('.export_excel').onclick = () => {
            window.open(
                `{{ route('admin.statistik.excel.JumlahDosenPembimbing') }}?programStudi=${tagify.value.map(tag => tag.value)}`,
                '_blank');
        };

        _JumlahDosenPembimbing(tagify.value.map(tag => tag.value));
    };
</script>
