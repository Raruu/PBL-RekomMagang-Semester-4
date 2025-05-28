@extends('layouts.app')
@section('title', 'Statistik')
@push('start')
    @vite(['resources/js/admin/statistik/index.js', 'resources/js/import/tagify.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-5 pb-5">
        @include('admin.statistik.magang-vs-tidak')
        @include('admin.statistik.tren-peminatan-mahasiswa')
    </div>

    <script>
        const run = () => {
            MagangVsTidak();
            PeminatanMahasiswa();
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
