@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="container-fluid p-0">
        {{-- Header Sambutan --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Dashboard Mahasiswa</h3>
                <p class="text-muted mb-0">Halo, <span class="text-primary fw-semibold">{{ $mahasiswa->nama }}</span>! Semangat kuliah hari ini! 🚀</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-white text-dark border shadow-sm py-2 px-3 rounded-pill">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 1. KARTU STATISTIK --}}
        <div class="row g-4 mb-4">
            {{-- IPK --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-primary-subtle text-primary rounded-3">
                                <i class="fas fa-graduation-cap fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">IPK Kumulatif</p>
                                <h3 class="fw-bold mb-0 text-dark">
                                    {{ !empty($chartIPKValues) ? number_format(end($chartIPKValues), 2) : '0.00' }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Total SKS --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-success-subtle text-success rounded-3">
                                <i class="fas fa-book-reader fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Total SKS Diambil</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalSksKrs ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Semester Aktif --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-info-subtle text-info rounded-3">
                                <i class="fas fa-layer-group fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Kelas / Semester</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $mahasiswa->kelas ?? '-' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- KOLOM KIRI (Grafik & Jadwal) --}}
            <div class="col-lg-8">
                {{-- Grafik Akademik --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0"><i class="fas fa-chart-line text-primary me-2"></i>Perkembangan Akademik</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(empty($chartLabels) || count($chartLabels) == 0)
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted opacity-50"><i class="fas fa-chart-area fa-3x"></i></div>
                                <p class="text-muted">Belum ada data nilai yang cukup untuk menampilkan grafik.</p>
                            </div>
                        @else
                            <div class="chart-container" style="position: relative; height:300px;">
                                <canvas id="ipkIpsChart"></canvas>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Jadwal Kuliah Hari Ini --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fas fa-calendar-day text-warning me-2"></i>Jadwal Hari Ini</h5>
                        <a href="{{ route('mahasiswa.lihatJadwalKuliah') }}" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">Lihat Semua</a>
                    </div>
                    <div class="card-body p-4">
                        @forelse($upcomingClasses as $jadwal)
                            <div class="d-flex align-items-center p-3 mb-3 bg-light rounded-3 border-start border-4 border-warning transition-hover">
                                <div class="me-3 text-center" style="min-width: 60px;">
                                    <span class="d-block fw-bold text-dark">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                                    <span class="small text-muted">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-dark mb-1">{{ optional($jadwal->mataKuliah)->nama_mk }}</h6>
                                    <div class="small text-muted">
                                        <i class="fas fa-chalkboard-teacher me-1"></i> {{ optional($jadwal->dosen)->nama }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark rounded-pill px-3">{{ $jadwal->ruangan }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-mug-hot fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">Tidak ada jadwal kuliah hari ini. Istirahat yang cukup!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Akses Cepat, Nilai, Pengumuman) --}}
            <div class="col-lg-4">
                {{-- Akses Cepat (Grid Buttons) --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-uppercase text-muted small mb-0">Menu Cepat</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('mahasiswa.lihatKRS') }}" class="btn btn-light w-100 p-3 h-100 text-start quick-btn border">
                                    <div class="icon-circle bg-primary-subtle text-primary mb-2"><i class="fas fa-tasks"></i></div>
                                    <span class="fw-bold d-block text-dark">Isi KRS</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('mahasiswa.lihatKHS') }}" class="btn btn-light w-100 p-3 h-100 text-start quick-btn border">
                                    <div class="icon-circle bg-success-subtle text-success mb-2"><i class="fas fa-file-invoice"></i></div>
                                    <span class="fw-bold d-block text-dark">Lihat KHS</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('mahasiswa.lihatJadwalKuliah') }}" class="btn btn-light w-100 p-3 h-100 text-start quick-btn border">
                                    <div class="icon-circle bg-info-subtle text-info mb-2"><i class="fas fa-calendar-alt"></i></div>
                                    <span class="fw-bold d-block text-dark">Jadwal</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('mahasiswa.presensi.form') }}" class="btn btn-light w-100 p-3 h-100 text-start quick-btn border">
                                    <div class="icon-circle bg-danger-subtle text-danger mb-2"><i class="fas fa-user-check"></i></div>
                                    <span class="fw-bold d-block text-dark">Presensi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nilai Terbaru --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-uppercase text-muted small mb-0">Nilai Masuk</h6>
                        <a href="{{ route('mahasiswa.lihatKHS') }}" class="small text-decoration-none">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recentGrades as $nilai)
                                <li class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="fw-bold text-primary">{{ substr(optional($nilai->mataKuliah)->nama_mk, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark small">{{ optional($nilai->mataKuliah)->nama_mk }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem">{{ optional($nilai->mataKuliah)->kode_mk }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary rounded-3 fs-6">{{ $nilai->nilai_huruf }}</span>
                                </li>
                            @empty
                                <li class="list-group-item border-0 text-center text-muted py-4 small">Belum ada nilai baru.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Pengumuman --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-uppercase text-muted small mb-0">Pengumuman</h6>
                    </div>
                    <div class="card-body p-4">
                        @forelse($announcements as $announcement)
                            <div class="mb-3 pb-3 border-bottom last-border-none">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-warning-subtle text-warning rounded-pill" style="font-size: 0.7rem">Info</span>
                                    <small class="text-muted" style="font-size: 0.7rem">{{ $announcement->created_at->diffForHumans() }}</small>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 small">{{ $announcement->title }}</h6>
                                <p class="text-muted mb-0 small text-truncate">{{ $announcement->content }}</p>
                            </div>
                        @empty
                            <div class="text-center text-muted small">Tidak ada pengumuman.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Shared Utilities */
        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }

        /* Quick Button Styling */
        .quick-btn {
            transition: all 0.2s ease;
            border-color: #f1f5f9 !important;
        }
        .quick-btn:hover {
            transform: translateY(-3px);
            background-color: #fff !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: var(--primary-color) !important;
        }
        .icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Utilities Colors */
        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
        .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
        .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
        .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
        .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }

        .last-border-none:last-child { border-bottom: none !important; margin-bottom: 0 !important; padding-bottom: 0 !important; }
        
        .transition-hover:hover {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data Grafik
            const chartLabels = @json($chartLabels);
            const chartIPSValues = @json($chartIPSValues);
            const chartIPKValues = @json($chartIPKValues);

            if (chartLabels.length > 0) {
                const ctx = document.getElementById('ipkIpsChart').getContext('2d');
                
                // Gradient untuk IPS
                let gradientIPS = ctx.createLinearGradient(0, 0, 0, 400);
                gradientIPS.addColorStop(0, 'rgba(67, 97, 238, 0.2)');
                gradientIPS.addColorStop(1, 'rgba(67, 97, 238, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'IPS (Indeks Prestasi Semester)',
                            data: chartIPSValues,
                            borderColor: '#4361ee',
                            backgroundColor: gradientIPS,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4361ee',
                            pointHoverBackgroundColor: '#4361ee',
                            pointHoverBorderColor: '#fff',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                        }, {
                            label: 'IPK (Kumulatif)',
                            data: chartIPKValues,
                            borderColor: '#3f37c9',
                            backgroundColor: 'transparent',
                            pointBackgroundColor: '#3f37c9',
                            borderDash: [5, 5],
                            tension: 0.4,
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    font: { family: "'Poppins', sans-serif", size: 12 }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#2b2d42',
                                bodyColor: '#2b2d42',
                                borderColor: '#e9ecef',
                                borderWidth: 1,
                                padding: 10,
                                boxPadding: 4
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 4.0,
                                grid: {
                                    borderDash: [4, 4],
                                    drawBorder: false,
                                    color: '#f0f0f0'
                                },
                                ticks: {
                                    font: { family: "'Poppins', sans-serif", size: 11 }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { family: "'Poppins', sans-serif", size: 11 }
                                }
                            }
                        },
                    }
                });
            }
        });
    </script>
@endpush