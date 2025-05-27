@extends('layouts.app')
@section('title', 'Statistik')
@push('start')
    @vite(['resources/js/admin/statistik/index.js'])
@endpush
@section('content')
    <div class="d-flex flex-column gap-2 pb-4">
        @include('admin.statistik.magang-vs-tidak')
    </div>

    <script>       
        const run = () => {
            MagangVsTidak();

            //  ......................
        };
        document.addEventListener('DOMContentLoaded', run);
    </script>
@endsection
