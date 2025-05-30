@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard Dosen</h1>

        <div class="card mb-4">
            <div class="card-header">
                <strong>Grafik Mahasiswa Bimbingan per Tahun</strong>
            </div>
            <div class="card-body">
                <canvas id="chartMahasiswa" height="100"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        fetch("{{ route('dosen.chart.mahasiswa') }}")
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.tahun);
                const values = data.map(item => item.total);

                const ctx = document.getElementById('chartMahasiswa').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Mahasiswa Bimbingan',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Mahasiswa Bimbingan per Tahun'
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
    </script>
@endsection
