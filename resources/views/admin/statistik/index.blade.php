@extends('layouts.app')
@section('title', 'Statistik')
@push('start')
    @vite(['resources/js/admin/statistik/index.js', 'resources/js/import/tagify.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-5 pb-5">
        @include('admin.statistik.magang-vs-tidak')
        @include('admin.statistik.tren-peminatan-mahasiswa')
        @include('admin.statistik.jumlah-dosen-pembimbing') {{-- + Jumlah Mahasiswa --}}
    </div>

    <script>
        const run = () => {
            const exportExcel = (target, url) => {
                btnSpinerFuncs.spinBtnSubmit(target);
                axios.get(
                    url, {
                        responseType: 'blob'
                    }).then(response => {
                    const fileName = response.headers['content-disposition'].split(';')[1].split('=')[1]
                        .trim().replace(/"/g, '');
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `${fileName}.xlsx`);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        title: `Gagal!`,
                        text: 'Terjadi kesalahan.',
                        icon: 'error'
                    });
                }).finally(() => {
                    btnSpinerFuncs.resetBtnSubmit(target);
                });
            };

            MagangVsTidak(exportExcel);
            PeminatanMahasiswa(exportExcel);
            JumlahDosenPembimbing(exportExcel);
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
