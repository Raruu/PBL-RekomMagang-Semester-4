@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column gap-4 pb-4 position-relative container-fluid">
        <div class="d-flex flex-column text-start gap-3 w-100">
            <div class="d-flex flex-row flex-wrap card px-3 py-4 mb-0">
                <div class="icon-wrapper me-3">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">Dashboard Statistik</h2>
                    <p class="text-muted mb-0">Kelola dan pantau statistik pengguna sistem</p>
                </div>
            </div>
        </div>

        <div class="card shadow-lg border-0 rounded-4">
            <div class="flex-row d-flex w-100 justify-content-between align-items-center p-4 bg-primary text-white rounded-top-4">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-users me-2"></i>
                    Statistik Pengguna
                </h5>
            </div>
            <div class="card-body py-4">
                <div class="row align-items-center g-3 mb-4 border-bottom pb-4">
                    <div class="col-auto">
                        <label for="userTypeSelect" class="form-label mb-0 fw-semibold">
                            Pilih Tipe Pengguna
                        </label>
                    </div>
                    <div class="col-auto">
                        <select id="userTypeSelect" class="form-select shadow-sm rounded-pill px-4 py-2 fw-semibold border-primary" style="min-width: 220px; max-width: 320px;">
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div id="statsContainer">
                    <div class="row g-4 mb-2">
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-gradient-primary text-white shadow-sm stat-card" data-filter="all">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="totalCount">0</div>
                                        <div class="small opacity-75">Total</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-gradient-success text-white shadow-sm stat-card" data-filter="active">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="activeCount">0</div>
                                        <div class="small opacity-75">Aktif</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card bg-gradient-danger text-white shadow-sm stat-card" data-filter="inactive">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="inactiveCount">0</div>
                                        <div class="small opacity-75">Nonaktif</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-user-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4" id="verifStatsRow" style="display:none;">
                        <div class="col-lg-4 col-md-6" id="verifiedCard">
                            <div class="card bg-gradient-info text-white shadow-sm stat-card" data-filter="verified">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="verifiedCount">0</div>
                                        <div class="small opacity-75">Terverifikasi</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6" id="memintaVerifCard">
                            <div class="card bg-gradient-warning text-white shadow-sm stat-card" data-filter="meminta_verif">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="memintaVerifCount">0</div>
                                        <div class="small opacity-75">Meminta Verif</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6" id="unverifiedCard">
                            <div class="card bg-gradient-secondary text-white shadow-sm stat-card" data-filter="unverified">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="h4 mb-0 fw-bold" id="unverifiedCount">0</div>
                                        <div class="small opacity-75">Belum Verif</div>
                                    </div>
                                    <div class="icon-wrapper-stat">
                                        <i class="fas fa-user-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Row -->
                <div class="row g-4 mt-2 mb-4">
                    <div class="col-12 col-md-6 col-lg-6 d-flex">
                        <div class="chart-container p-3 bg-white shadow-sm border rounded-4 flex-grow-1 w-100">
                            <div class="fw-semibold mb-2 text-primary"><i class="fas fa-chart-pie me-2"></i>Statistik Aktivitas</div>
                            <div class="chart-wrapper"><canvas id="activityChart"></canvas></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex" id="verifChartContainer" style="display:none;">
                        <div class="chart-container p-3 bg-white shadow-sm border rounded-4 flex-grow-1 w-100">
                            <div class="fw-semibold mb-2 text-info"><i class="fas fa-certificate me-2"></i>Status Verifikasi</div>
                            <div class="chart-wrapper"><canvas id="verificationChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 rounded-4">
                            <div class="card-body p-3">
                                <div class="row g-3 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-sm rounded-3 shadow-sm fw-semibold py-2 px-2 fs-6 action-btn"
                                                id="manageAllBtn">
                                                <i class="fas fa-cog me-2"></i>
                                                Kelola Semua <span id="userTypeActionLabel">Pengguna</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="verificationActionContainer" style="display: none;">
                                        <div class="d-grid">
                                            <button class="btn btn-info btn-sm rounded-3 shadow-sm fw-semibold py-2 px-2 fs-6 action-btn"
                                                id="verificationBtn">
                                                <i class="fas fa-certificate me-2"></i>
                                                Kelola Verifikasi
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-grid">
                                            <button class="btn btn-success btn-sm rounded-3 shadow-sm fw-semibold py-2 px-2 fs-6 action-btn"
                                                id="refreshStatsBtn">
                                                <i class="fas fa-sync-alt me-2"></i>
                                                Refresh Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card d-flex flex-column gap-2 mt-1 shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="flex-row d-flex w-100 justify-content-between align-items-center p-4 bg-success text-white rounded-top-4"
                style="background: linear-gradient(90deg, #28a745 60%, #218838 100%); box-shadow: 0 4px 16px rgba(40,167,69,0.15);">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2"><i class="fas fa-comment-dots me-2"></i>Feedback SPK
                    dari Mahasiswa</h5>
                <a href="{{ route('admin.evaluasi.spk.feedback') }}"
                    class="btn btn-outline-light mx-2 d-flex flex-row gap-2 align-items-center shadow-sm rounded-pill px-4 py-2 fw-semibold">
                    Lihat Kotak Feedback <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="flex-row w-100 p-4 d-flex gap-5 align-items-center justify-content-between flex-wrap">
                <div class="d-flex flex-column gap-2 align-items-center justify-content-center" style="min-width: 320px;">
                    <h6 class="fw-bold mb-3">Persentase Kepuasan</h6>
                    <div class="position-relative" style="width: 220px; height: 220px;">
                        <canvas id="percent-puas-chart" style="width: 100%; height: 100%;"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle text-center"
                            style="pointer-events: none;">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <table class="table table-borderless mt-3 mb-0 align-middle">
                        <tbody>
                            <tr>
                                <td style="width: 86px;">
                                    <div class="rating-label">
                                        <span class="badge bg-success fw-bold">Rating 5</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress rounded-pill" style="height: 18px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $feedbackRating[4] == 0 ? 0 : ($feedbackRating[4] / $feedbackRatingTotal) * 100 }}%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-semibold ms-2">{{ $feedbackRating[4] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 86px;">
                                    <div class="rating-label">
                                        <span class="badge bg-success fw-bold">Rating 4</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress rounded-pill" style="height: 18px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $feedbackRating[3] == 0 ? 0 : ($feedbackRating[3] / $feedbackRatingTotal) * 100 }}%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-semibold ms-2">{{ $feedbackRating[3] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 86px;">
                                    <div class="rating-label">
                                        <span class="badge bg-info fw-bold">Rating 3</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress rounded-pill" style="height: 18px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ $feedbackRating[2] == 0 ? 0 : ($feedbackRating[2] / $feedbackRatingTotal) * 100 }}%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-semibold ms-2">{{ $feedbackRating[2] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 86px;">
                                    <div class="rating-label">
                                        <span class="badge bg-warning fw-bold">Rating 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress rounded-pill" style="height: 18px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                            style="width: {{ $feedbackRating[1] == 0 ? 0 : ($feedbackRating[1] / $feedbackRatingTotal) * 100 }}%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-semibold ms-2">{{ $feedbackRating[1] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 86px;">
                                    <div class="rating-label">
                                        <span class="badge bg-danger fw-bold">Rating 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress rounded-pill" style="height: 18px;">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                            style="width: {{ $feedbackRating[0] == 0 ? 0 : ($feedbackRating[0] / $feedbackRatingTotal) * 100 }}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            <span class="fw-semibold ms-2">{{ $feedbackRating[0] }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            background-color: var(--cui-primary-bg-subtle);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .icon-wrapper i {
            font-size: 28px;
            color: var(--cui-primary);
        }

        .icon-wrapper-stat {
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper-stat i {
            font-size: 24px;
            color: white;
        }

        .icon-wrapper-stat-sm {
            width: 35px;
            height: 35px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper-stat-sm i {
            font-size: 16px;
            color: white;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            transition: all 0.3s ease;
        }

        .stat-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .loading-text {
            color: #6c757d;
            font-style: italic;
        }

        [data-coreui-theme="light"] .chart-container {
            background-color: #F8FAFC;
        }
        [data-coreui-theme="dark"] .chart-container {
            background-color: #23272f;
            border-color:rgb(87, 87, 87);
        }

        .chart-container {
            border-radius: 16px !important;
            overflow: hidden;
        }

        .chart-wrapper {
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .chart-wrapper canvas {
            max-height: 240px;
        }

        .rating-label {
            min-width: 100px;
            text-align: center;
        }

        .rating-label .badge {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 20px;
        }

        .action-btn {
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateY(-3px);
        }

        .sticky-dashboard-header {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .col-lg-6 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .icon-wrapper i {
                font-size: 20px;
            }
        }
    </style>
@endpush

@push('end')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let activityChart = null;
        let verificationChart = null;
        let currentUserType = 'mahasiswa'; // Set default to mahasiswa

        // Chart Persentase Kepuasan
        const initSatisfactionPercentageChart = () => {
            const ctx = document.getElementById('percent-puas-chart').getContext('2d');
            // Data dari controller
            const feedbackData = {
                labels: ["Puas", "Tidak Puas"],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#089cfc', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            };
            // Hitung persentase kepuasan
            const calculateSatisfaction = () => {
                const feedbackRating = @json($feedbackRating);
                const feedbackRatingTotal = {{ $feedbackRatingTotal }};
                if (feedbackRatingTotal === 0) return 0;
                const weightedSum = (1 * feedbackRating[0]) +
                    (2 * feedbackRating[1]) +
                    (3 * feedbackRating[2]) +
                    (4 * feedbackRating[3]) +
                    (5 * feedbackRating[4]);
                return (weightedSum / (feedbackRatingTotal * 5)) * 100;
            };
            const satisfactionPercentage = calculateSatisfaction();
            feedbackData.datasets[0].data = [
                satisfactionPercentage,
                100 - satisfactionPercentage
            ];
            return new Chart(ctx, {
                type: 'doughnut',
                data: feedbackData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    cutout: '70%'
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: function (chart) {
                        const width = chart.width;
                        const height = chart.height;
                        const ctx = chart.ctx;
                        ctx.restore();
                        const fontSize = (height / 144).toFixed(2);
                        ctx.font = fontSize + "em sans-serif";
                        ctx.textBaseline = "middle";
                        const text = satisfactionPercentage.toFixed(1) + "%";
                        const textX = Math.round((width - ctx.measureText(text).width) / 2);
                        const textY = height / 2;
                        ctx.fillStyle = getComputedStyle(document.documentElement)
                            .getPropertyValue('--foreground');
                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }]
            });
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Set default select value to mahasiswa
            $('#userTypeSelect').val('mahasiswa');
            // Show statsContainer by default
            $('#statsContainer').removeClass('d-none');
            // Load stats for mahasiswa on page load
            loadUserStats('mahasiswa');

            $('#userTypeSelect').change(function () {
                const selectedType = $(this).val();
                if (selectedType) {
                    currentUserType = selectedType;
                    loadUserStats(selectedType);
                    $('#statsContainer').removeClass('d-none');
                } else {
                    $('#statsContainer').addClass('d-none');
                    currentUserType = '';
                }
            });

            // Event listener for stat card clicks
            $(document).on('click', '.stat-card', function () {
                const filter = $(this).data('filter');
                navigateToUserPage(currentUserType, filter);
            });

            // Event listener for quick action buttons
            $('#manageAllBtn').click(function () {
                if (currentUserType) {
                    navigateToUserPage(currentUserType, 'all');
                }
            });

            $('#verificationBtn').click(function () {
                if (currentUserType === 'mahasiswa') {
                    navigateToUserPage(currentUserType, 'unverified');
                }
            });

            // Refresh button
            $('#refreshStatsBtn').click(function () {
                if (currentUserType) {
                    loadUserStats(currentUserType);
                    $(this).find('i').addClass('fa-spin');
                    setTimeout(() => {
                        $(this).find('i').removeClass('fa-spin');
                    }, 1000);
                }
            });

            // Inisialisasi chart persentase kepuasan
            if (document.getElementById('percent-puas-chart')) {
                window.satisfactionChart = initSatisfactionPercentageChart();
            }

            // Update style saat tema berubah
            const updateChartStyles = () => {
                if (window.satisfactionChart) {
                    const ctx = window.satisfactionChart.ctx;
                    ctx.fillStyle = getComputedStyle(document.documentElement)
                        .getPropertyValue('--foreground');
                    window.satisfactionChart.update();
                }
            };
            document.documentElement.addEventListener('ColorSchemeChange', updateChartStyles);
        });

        function loadUserStats(userType) {
            // Show loading state
            showLoading();

            $.ajax({
                url: '{{ route("admin.user-stats") }}',
                method: 'GET',
                data: { type: userType },
                success: function (response) {
                    console.log('Stats Response:', response);
                    updateStatsDisplay(response);
                    updateCharts(response);
                },
                error: function (xhr) {
                    console.error('Error loading stats:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat statistik pengguna'
                    });
                    hideLoading();
                }
            });
        }

        function updateStatsDisplay(stats) {
            $('#totalCount').text(stats.total || 0);
            $('#activeCount').text(stats.active || 0);
            $('#inactiveCount').text(stats.inactive || 0);
            $('#userTypeLabel').text(stats.type || 'Pengguna');
            $('#userTypeActionLabel').text(stats.type || 'Pengguna');

            // Show/hide verification row based on user type
            if (stats.type === 'Mahasiswa') {
                $('#verifStatsRow').show();
                $('#verifiedCount').text(stats.verified || 0);
                $('#unverifiedCount').text(stats.unverified || 0);
                $('#memintaVerifCount').text(stats.meminta_verif || 0);
            } else {
                $('#verifStatsRow').hide();
            }
        }

        function updateCharts(stats) {
            // Activity Chart
            const activityCtx = document.getElementById('activityChart').getContext('2d');

            if (activityChart) {
                activityChart.destroy();
            }

            activityChart = new Chart(activityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Nonaktif'],
                    datasets: [{
                        data: [stats.active || 0, stats.inactive || 0],
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Verification Chart (only for mahasiswa)
            if (stats.type === 'Mahasiswa' && stats.hasOwnProperty('verified')) {
                const verificationCtx = document.getElementById('verificationChart').getContext('2d');

                if (verificationChart) {
                    verificationChart.destroy();
                }

                verificationChart = new Chart(verificationCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Terverifikasi', 'Belum Terverifikasi'],
                        datasets: [{
                            data: [stats.verified || 0, stats.unverified || 0],
                            backgroundColor: ['#17a2b8', '#ffc107'],
                            borderWidth: 3,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        }

        function navigateToUserPage(userType, filter) {
            let baseUrl = '';
            if (userType === 'mahasiswa') {
                baseUrl = '/admin/pengguna/mahasiswa';
            } else if (userType === 'dosen') {
                baseUrl = '/admin/pengguna/dosen';
            } else if (userType === 'admin') {
                baseUrl = '/admin/pengguna/admin';
            }
            if (baseUrl) {
                if (filter && filter !== 'all') {
                    window.location.href = baseUrl + '?filter=' + filter;
                } else {
                    window.location.href = baseUrl;
                }
            }
        }

        function showLoading() {
            $('#totalCount, #activeCount, #inactiveCount, #verifiedCount, #unverifiedCount').html('<div class="spinner-border spinner-border-sm" role="status"></div>');
        }

        function hideLoading() {
            $('#totalCount, #activeCount, #inactiveCount, #verifiedCount, #unverifiedCount').text('0');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const contentMid = document.querySelector('#content-mid');
            if (contentMid) {
                contentMid.style.maxHeight = 'calc(100vh - 65px)';
            }
        })
    </script>
@endpush