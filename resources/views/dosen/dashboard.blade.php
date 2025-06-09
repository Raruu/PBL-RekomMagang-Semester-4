@extends('layouts.app')

@section('content')
    <div class="d-flex flex-row gap-4 pb-4 main-content">


        <div class="d-flex flex-column gap-3 flex-fill">
            <h4 class="fw-bold mb-0">Ringkasan Bimbingan</h4>


            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang</h5>
                    <p class="card-text">Halo, <strong>{{ Auth::user()->profilDosen->nama }}</strong>. Berikut adalah
                        ringkasan jumlah mahasiswa yang Anda bimbing.</p>
                </div>
            </div>


            <div class="d-flex flex-row gap-3 flex-wrap">

                <div class="card text-center flex-fill" style="min-width: 200px;">
                    <div class="card-body bg-success text-white rounded-3 shadow-sm">
                        <i class="bi bi-people-fill display-5 mb-2"></i>
                        <h5 class="card-title">Mahasiswa Yang Sedang Dibimbing</h5>
                        <p class="card-text display-6 fw-bold">{{ $dibimbing }}</p>
                    </div>
                </div>

                <div class="card text-center flex-fill" style="min-width: 200px;">
                    <div class="card-body bg-info text-white rounded-3 shadow-sm d-flex flex-column align-items-center">
                        <i class="bi bi-people-fill display-5 mb-2"></i>
                        <h5 class="card-title">Total Mahasiswa Dibimbing</h5>
                        <p class="card-text display-6 fw-bold">{{ $dibimbing + $selesai }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (e) => {
            const mediaQuery = (result) => {
                const mainContent = document.querySelector('.main-content');
                const infoLeftWrapper = document.querySelector('.info_left_wrapper');
                if (!mainContent || !infoLeftWrapper) return;
                switch (result) {
                    case 'xs':
                    case 'sm':
                        mainContent.classList.remove('flex-row');
                        infoLeftWrapper.classList.remove('width-334');
                        mainContent.classList.add('flex-column');
                        break;
                    default:
                        mainContent.classList.remove('flex-column');
                        mainContent.classList.add('flex-row');
                        infoLeftWrapper.classList.add('width-334');
                        break;
                }
            };
            useMediaQuery.arr.push(mediaQuery);
        });
    </script>
@endsection