@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid p-0">
        {{-- Header Sambutan --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Dashboard Overview</h3>
                <p class="text-muted mb-0">Selamat Datang kembali, <span class="text-primary fw-semibold">{{ Auth::user()->name }}!</span> 👋</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-white text-dark border shadow-sm py-2 px-3 rounded-pill">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        {{-- 1. KARTU KPI (Tetap di Atas) --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Total Pengguna --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-primary-subtle text-primary rounded-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Total Pengguna</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalUsers ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Dosen --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-info-subtle text-info rounded-3">
                                <i class="fas fa-chalkboard-teacher fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Total Dosen</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalDosen ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Mahasiswa --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-success-subtle text-success rounded-3">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Mahasiswa</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalMahasiswa ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Mata Kuliah --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-warning-subtle text-warning rounded-3">
                                <i class="fas fa-book-open fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted text-uppercase fw-bold mb-0 small">Mata Kuliah</p>
                                <h4 class="fw-bold mb-0 text-dark">{{ $totalMataKuliah ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. FORM PENGUMUMAN (Full Width) --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white pt-4 px-4 pb-0 border-0">
                        <h5 class="fw-bold text-dark"><i class="fas fa-bullhorn text-primary me-2"></i>Buat Pengumuman Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="announcement-form" action="{{ route('admin.announcements.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Judul</label>
                                    <input type="text" id="announcement_title" name="title" class="form-control bg-light border-0" placeholder="Judul Pengumuman...">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Target</label>
                                    <select id="target_role" name="target_role" class="form-select bg-light border-0">
                                        <option value="Semua">📢 Semua</option>
                                        <option value="Dosen">👨‍🏫 Dosen</option>
                                        <option value="Mahasiswa">🎓 Mahasiswa</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Isi Pesan</label>
                                    <textarea id="announcement_content" name="content" class="form-control bg-light border-0" rows="3" placeholder="Ketik isi pengumuman di sini..."></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4" id="submit-announcement">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pengumuman
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. TABEL STATISTIK / RINGKASAN DATA (Di Bawah, Full Width) --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0"><i class="fas fa-chart-pie text-primary me-2"></i>Ringkasan Data Statistik</h5>
                            <button class="btn btn-sm btn-outline-light text-muted border"><i class="fas fa-sync-alt"></i> Refresh</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small text-uppercase fw-bold" style="width: 30%;">Entitas Data</th>
                                        <th class="py-3 text-muted small text-uppercase fw-bold">Status</th>
                                        <th class="py-3 text-center text-muted small text-uppercase fw-bold">Total Record</th>
                                        <th class="pe-4 py-3 text-end text-muted small text-uppercase fw-bold">Terakhir Diupdate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataSummary as $jenis => $data)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                                        <i class="fas fa-database"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-dark">{{ $jenis }}</h6>
                                                        <small class="text-muted">Data Master</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Active</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fs-5 fw-bold text-dark">{{ $data['count'] ?? '0' }}</span>
                                            </td>
                                            <td class="text-end pe-4 text-muted">
                                                @if ($data['last_updated'])
                                                    <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($data['last_updated'])->diffForHumans() }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        /* Form styling */
        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
        .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
        .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
        .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    </style>
@endpush

@push('scripts')
    <script>
        $('#announcement-form').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const submitButton = $('#submit-announcement');
            
            submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
            $('.form-control, .form-select').removeClass('is-invalid');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    submitButton.prop('disabled', false).html('<i class="fas fa-check me-2"></i>Terkirim');
                    setTimeout(() => {
                        submitButton.html('<i class="fas fa-paper-plane me-2"></i>Kirim Pengumuman');
                    }, 2000);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    form.trigger('reset');
                },
                error: function (xhr) {
                    submitButton.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Kirim Pengumuman');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $(`#announcement_${key}`).addClass('is-invalid').siblings('.invalid-feedback').text(value[0]);
                        });
                        if(errors.target_role) $('#target_role').addClass('is-invalid');
                    }
                }
            });
        });
    </script>
@endpush