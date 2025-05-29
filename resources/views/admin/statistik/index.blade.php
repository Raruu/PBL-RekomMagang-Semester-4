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
            MagangVsTidak();
            PeminatanMahasiswa();
            JumlahDosenPembimbing(); 
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
