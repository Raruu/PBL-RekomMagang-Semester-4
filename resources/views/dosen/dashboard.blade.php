@extends('layouts.app')

@section('content')
    <div class="d-flex flex-row gap-4 pb-4 main-content">
        <div class="d-flex flex-column gap-3 flex-fill">
            <h4 class="fw-bold mb-0">Ringkasan Bimbingan</h4>

            <!-- Welcome Card -->
            <div class="card w-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang</h5>
                    <p class="card-text">Halo, <strong>{{ Auth::user()->profilDosen->nama }}</strong>. Berikut adalah
                        ringkasan jumlah mahasiswa yang Anda bimbing.</p>
                </div>
            </div>

            <!-- Bimbingan Cards -->
            <div class="d-flex flex-row gap-3 flex-wrap">
                <!-- Mahasiswa Sedang Dibimbing -->
                <a href="{{ route('dosen.mahasiswabimbingan') }}" class="text-decoration-none flex-fill" style="min-width: 200px;">
                    <div class="card text-center shadow-lg h-100 hover-shadow transition" style="cursor: pointer;">
                        <div class="card-body bg-success text-white rounded-3 d-flex flex-column align-items-center">
                            <i class="bi bi-person-check-fill display-5 mb-2"></i>
                            <h5 class="card-title">Mahasiswa Sedang Dibimbing</h5>
                            <p class="card-text display-6 fw-bold">{{ $dibimbing }}</p>
                        </div>
                    </div>
                </a>

                <!-- Total Mahasiswa Dibimbing -->
                <a href="{{ route('dosen.mahasiswabimbingan') }}" class="text-decoration-none flex-fill" style="min-width: 200px;">
                    <div class="card text-center shadow-lg h-100 hover-shadow transition" style="cursor: pointer;">
                        <div class="card-body bg-info text-white rounded-3 d-flex flex-column align-items-center">
                            <i class="bi bi-people-fill display-5 mb-2"></i>
                            <h5 class="card-title">Total Mahasiswa Dibimbing</h5>
                            <p class="card-text display-6 fw-bold">{{ $dibimbing + $selesai }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Tambahan gaya hover untuk interaksi */
        .hover-shadow:hover {
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }

        .transition {
            transition: all 0.2s ease-in-out;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const contentMid = document.querySelector('#content-mid');
            if (contentMid) {
                contentMid.style.maxHeight = 'calc(100vh - 65px)';
            }
        });
    </script>
@endsection
