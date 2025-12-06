@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
    <div class="container-fluid p-0">
        {{-- Header Sambutan --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Dashboard Dosen</h3>
                <p class="text-muted mb-0">Selamat Datang, <span class="text-primary fw-semibold">{{ $dosen->nama }}</span>! 👋</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-white text-dark border shadow-sm py-2 px-3 rounded-pill">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Kartu Statistik Modern --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Mata Kuliah --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-primary-subtle text-primary rounded-3">
                                <i class="fas fa-book-open fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Mata Kuliah</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalMataKuliahDiajar ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="mt-2">
                             <span class="text-muted small">Kelas Diampu</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Jadwal Mengajar --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-info-subtle text-info rounded-3">
                                <i class="fas fa-calendar-day fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Total Jadwal</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalJadwalMengajar ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Sesi Pertemuan</span>
                       </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Mahasiswa Bimbingan --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-success-subtle text-success rounded-3">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Bimbingan</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalMahasiswaBimbingan ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Mahasiswa Wali</span>
                       </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Nilai Diinput --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-warning-subtle text-warning rounded-3">
                                <i class="fas fa-clipboard-check fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Nilai Masuk</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalNilaiDiinput ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-muted small">Data Terverifikasi</span>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="row g-4">
            {{-- Kolom Kiri: Jadwal Mengajar --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="fas fa-chalkboard-teacher text-primary me-2"></i>Jadwal Mengajar Terdekat</h5>
                            <a href="{{ route('dosen.lihatJadwalMengajar') }}" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($upcomingSchedules->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted opacity-50">
                                    <i class="fas fa-calendar-times fa-3x"></i>
                                </div>
                                <h6 class="text-muted fw-normal">Tidak ada jadwal mengajar dalam waktu dekat.</h6>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-3">
                                @foreach($upcomingSchedules as $jadwal)
                                    <div class="p-3 rounded-3 border bg-light position-relative schedule-item transition-hover">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="badge bg-primary mb-2">{{ $jadwal->hari }}</span>
                                                <h6 class="fw-bold text-dark mb-1">{{ optional($jadwal->mataKuliah)->nama_mk }}</h6>
                                                <div class="d-flex align-items-center text-muted small mt-2">
                                                    <span class="me-3"><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                                    <span><i class="fas fa-map-marker-alt me-1"></i> {{ $jadwal->ruangan }}</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-muted small mb-1">Kelas</div>
                                                <span class="fw-bold text-dark fs-5">{{ $jadwal->kelas }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pengumuman --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0"><i class="fas fa-bullhorn text-warning me-2"></i>Pengumuman Terbaru</h5>
                    </div>
                    <div class="card-body p-0 pt-2">
                        @forelse($announcements as $announcement)
                            <div class="p-4 border-bottom last-border-none hover-bg-light transition-hover">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-warning-subtle text-warning fw-normal rounded-pill px-2">Info</span>
                                    <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">{{ $announcement->title }}</h6>
                                <p class="text-muted small mb-0 line-clamp-2">{{ $announcement->content }}</p>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3 text-muted opacity-50">
                                    <i class="fas fa-inbox fa-3x"></i>
                                </div>
                                <h6 class="text-muted fw-normal">Belum ada pengumuman baru.</h6>
                            </div>
                        @endforelse
                    </div>
                    @if($announcements->isNotEmpty())
                        <div class="card-footer bg-white border-0 text-center pb-4">
                            <button class="btn btn-link text-decoration-none text-muted small">Lihat Arsip Pengumuman</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Shared Styles (Sama seperti Admin Dashboard) */
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

        /* Utilities Colors */
        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
        .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
        .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
        .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
        
        /* Schedule Item Styling */
        .schedule-item {
            border-left: 4px solid var(--primary-color) !important;
            background-color: #fff !important; /* Overwrite bg-light for clearer look */
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        /* Hover Effects */
        .transition-hover {
            transition: all 0.2s ease;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }
        .last-border-none:last-child {
            border-bottom: none !important;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush