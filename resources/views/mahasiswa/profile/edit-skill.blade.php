<div class="d-flex flex-column gap-3 flex-fill">
    <h4 class="fw-bold mb-0">Keahlian</h4>
    <div class="d-flex flex-column text-start  card p-3" style="height: fit-content;">
        @foreach ($tingkat_kemampuan as $keytingkatKemampuan => $tingkatKemampuan)
            <div class="mb-3" >
                <p class="mb-0 fw-bold">{{ $tingkatKemampuan }}</p>
                <input type="text" class="form-control" name="keahlian-{{ $keytingkatKemampuan }}"
                    id="keahlian-{{ $keytingkatKemampuan }}"
                    value="{{ implode(', ', $keahlian_mahasiswa->where('tingkat_kemampuan', $keytingkatKemampuan)->pluck('keahlian.nama_keahlian')->toArray()) }}">
                <div id="error-keahlian-{{ $keytingkatKemampuan }}" class="text-danger"></div>
            </div>
        @endforeach
    </div>
</div>
