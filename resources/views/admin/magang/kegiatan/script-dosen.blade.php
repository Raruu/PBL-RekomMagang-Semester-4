<script>
    const fetchDosen = (dosen_id) => {
        const displayDosen = document.querySelector('.display_dosen');
        if (!displayDosen) return;
        const textPilihDosen = displayDosen.querySelector('.pilih_dosen');
        textPilihDosen.classList.remove('d-none');
        if (dosen_id == '') return;
        textPilihDosen.classList.add('d-none');
        const spinner = displayDosen.querySelector('#fetch-loading');
        spinner.classList.remove('d-none');
        const displayCtn = displayDosen.querySelector('.display_dosen_content');
        displayCtn.classList.add('d-none');

        axios.get(`{{ route('admin.magang.kegiatan.getDosenData', ['dosen_id' => ':id']) }}`.replace(
                ':id',
                dosen_id))
            .then(response => {
                const data = response.data.data;
                displayDosen.querySelector('#picture-display').src = data.foto_profil ? data.foto_profil :
                    '{{ asset('imgs/profile_placeholder.webp') }}?{{ now() }}';
                displayDosen.querySelector('.dosen_nama').textContent = data.nama;
                displayDosen.querySelector('.dosen_nip').textContent = data.nip;
                displayDosen.querySelector('.dosen_program').textContent = data.program_studi.nama_program;
                displayDosen.querySelector('.dosen_minat').textContent = data.minat_penelitian;
                displayDosen.querySelector('.dosen_telp').textContent = data.nomor_telepon;
                displayDosen.querySelector('.dosen_email').textContent = data.user.email;

                spinner.classList.add('d-none');
                displayCtn.classList.remove('d-none');
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    title: `Gagal!`,
                    text: error.response || 'Terjadi kesalahan.',
                    icon: 'error'
                });
            });
    };
</script>
