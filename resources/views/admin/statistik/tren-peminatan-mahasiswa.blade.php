<div class="d-flex flex-column gap-2 tren_peminatan_mahasiswa" id="tren_peminatan_mahasiswa">
    <div class="d-flex flex-row gap-2 w-100 justify-content-between">
        <h5 class="fw-bold">Tren Peminatan Mahasiswa Terhadap Bidang Industri Tertentu</h5>
        <button type="button" class="btn btn-outline-success export_excel">
            <i class="fas fa-file-excel"></i>
        </button>

    </div>
    <div class="card flex-column p-3 d-flex gap-3">
        <div class="card flex-column p-3 gap-3 w-100">
            <div class="input-group">
                <label class="input-group-text">Bidang Industri</label>
                <input type="text" class="form-control bidang_industri" value="{{ implode(', ', $bidangIndustri) }}">
            </div>
            <div class="form-check form-switch d-flex flex-row align-items-center gap-2">
                <input class="form-check-input is_stacked" for="is_stacked" type="checkbox" checked>
                <label class="form-check-label fs-5 mx-0" id="is_stacked">
                    Stacked?
                </label>
            </div>
        </div>
        <div class="card flex-row p-3 gap-3 w-100">
            <div class="input-group">
                <label class="input-group-text">Start</label>
                <select class="form-select start_peminatan-mahasiswa">
                    @for ($i = date('Y'); $i >= 2015; $i--)
                        <option value="{{ $i }}" {{ $i == 2019 ? 'selected' : '' }}>{{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="input-group">
                <label class="input-group-text">End</label>
                <select class="form-select end_peminatan-mahasiswa">
                    @for ($i = date('Y'); $i >= 2019; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="flex-fill" style="height: 100px"><canvas id="chart-peminatan-mahasiswa"></canvas></div>
    </div>
</div>

<script>
    const _PeminatanMahasiswa = async (start, end, tags) => {
        try {
            const response = await axios.get('{{ route('admin.statistik.get.TrenPeminatanMahasiswa') }}', {
                params: {
                    start: start,
                    end: end,
                    tags: tags
                }
            });

            const data = Object.values(response.data)[0];
            const labels = Object.keys(data);
            const values = Object.values(data);
            const labelDatasets = Object.keys(data[labels[0]]);
            const dataValue = values.map(item => Object.values(item)).reduce(
                (prev, next) => next.map((item, i) => (prev[i] || []).concat(item)),
                []
            );

            const isStacked = document.querySelector('.tren_peminatan_mahasiswa .is_stacked').checked;

            chartPeminatanMahasiswa.setData(labels, labelDatasets, dataValue, isStacked);
            chartPeminatanMahasiswa.chart.canvas.parentElement.style.height = `${getCanvasHeight()}px`;
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: `Gagal!`,
                text: error.response || 'Terjadi kesalahan.',
                icon: 'error'
            });
        }
    };

    const PeminatanMahasiswa = () => {
        const trenMahasiswa = document.querySelector('.tren_peminatan_mahasiswa');
        const tagify = new Tagify(trenMahasiswa.querySelector('.bidang_industri'), {
            whitelist: @json($bidangIndustri),
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

        const dragsort = new DragSort(tagify.DOM.scope, {
            selector: '.' + tagify.settings.classNames.tag,
            callbacks: {
                dragEnd: () => {
                    tagifyUtils.onDragEnd(tagify);
                }
            }
        })

        const startPeminatanMahasiswa = trenMahasiswa.querySelector('.start_peminatan-mahasiswa');
        const endPeminatanMahasiswa = trenMahasiswa.querySelector('.end_peminatan-mahasiswa');

        startPeminatanMahasiswa.addEventListener('change', () => {
            _PeminatanMahasiswa(startPeminatanMahasiswa.value, endPeminatanMahasiswa.value, tagify.value
                .map(tag => tag.value));
        });
        endPeminatanMahasiswa.addEventListener('change', () => {
            _PeminatanMahasiswa(startPeminatanMahasiswa.value, endPeminatanMahasiswa.value, tagify.value
                .map(tag => tag.value));
        });
        tagify.on('change', e => {
            _PeminatanMahasiswa(startPeminatanMahasiswa.value, endPeminatanMahasiswa.value, tagify.value
                .map(tag => tag.value));
        });

        trenMahasiswa.querySelector('.is_stacked').addEventListener('change', () => {
            _PeminatanMahasiswa(startPeminatanMahasiswa.value, endPeminatanMahasiswa.value, tagify.value
                .map(tag => tag.value));
        })

        trenMahasiswa.querySelector('.export_excel').onclick = () => {
            window.open(
                `{{ route('admin.statistik.excel.TrenPeminatanMahasiswa') }}?start=${startPeminatanMahasiswa.value}&end=${endPeminatanMahasiswa.value}&tags=${tagify.value.map(tag => tag.value)}`,
                '_blank');
        };


        _PeminatanMahasiswa(startPeminatanMahasiswa.value, endPeminatanMahasiswa.value, tagify.value.map(tag => tag
            .value));
    };
</script>
