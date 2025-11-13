@extends('components.layout')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::guard('pengguna')->user();
@endphp

<style>
    .stats-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .stats-card .card-body {
        padding: 24px;
    }

    .stats-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stats-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-gradient-icon {
        background: linear-gradient(135deg, #718355 0%, #6B9071 100%);
    }

    
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 20px;
        color: white;
    }

    .welcome-card .card-body {
        padding: 40px;
    }

    .detail-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    }

    .detail-card .card-header {
        border: none;
        border-radius: 16px 16px 0 0 !important;
        padding: 20px 24px;
    }

    .badge-custom {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .progress {
        height: 10px;
        border-radius: 10px;
        background-color: #e2e8f0;
    }

    .progress-bar {
        border-radius: 10px;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
    }
</style>

<main id="main" class="main">
    <!-- Page Title -->
    <div class="pagetitle mb-4">
        <h1 class="mb-2">
            <i class="fas fa-tachometer-alt  me-2"></i>
            Dashboard
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#"><i class="fas fa-home me-1"></i> Home</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Rencana Aksi Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body">
                        <div class="stats-icon bg-gradient-icon text-white">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stats-number text-dark">{{$totalRencanaAksi}}</div>
                        <div class="stats-label">Rencana Aksi</div>
                    </div>
                </div>
            </div>

            <!-- Rencana Kerja Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body">
                        <div class="stats-icon bg-gradient-icon text-white">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stats-number text-dark">{{ $totalRencanaKerja }}</div>
                        <div class="stats-label">Rencana Kerja</div>
                    </div>
                </div>
            </div>

            <!-- Monitoring Evaluasi Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body">
                        <div class="stats-icon bg-gradient-icon text-white">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stats-number text-dark">{{ $totalMonev }}</div>
                        <div class="stats-label">Monitoring Evaluasi</div>
                    </div>
                </div>
            </div>
            <!-- Progres Kegiatan Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card shadow-sm">
                    <div class="card-body">
                        <div class="stats-icon bg-gradient-icon text-white">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stats-number text-dark">{{ $totalProgresKerja }}</div>
                        <div class="stats-label">Progres Kegiatan</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Welcome Card -->
        {{-- <div class="row mb-4">
            <div class="col-12">
                <div class="card welcome-card shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2 fw-bold">
                                    Selamat Datang, <span class="text-warning">{{ $user->nama }}</span>! 👋
                                </h2>
                                <p class="mb-3 opacity-90 fs-6">
                                    Semoga hari Anda menyenangkan. Mari bersama kita kelola data dengan
                                    lebih efisien, akurat, dan transparan.
                                </p>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge-custom bg-white bg-opacity-25">
                                        <i class="fas fa-user me-2"></i>
                                        Level: <strong>{{ $user->level ?? 'User' }}</strong>
                                    </span>
                                    <span class="badge-custom bg-white bg-opacity-25">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ date('d M Y') }}
                                    </span>
                                    <span class="badge-custom bg-white bg-opacity-25">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ date('H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4 text-center d-none d-md-block">
                                <div class="position-relative">
                                    <i class="fas fa-chart-bar" style="font-size: 120px; opacity: 0.2;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Detail Cards -->
        <div class="row g-4">
            <!-- Rencana Kerja Detail -->
            <div class="col-lg-6">
                <div class="card detail-card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-bold">
                                    <i class="fas fa-tasks me-2"></i> Rencana Kerja
                                </h5>
                                <small class="opacity-75">Manajemen rencana kerja</small>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-bold">{{ $totalRencanaKerja }}</div>
                                <small class="opacity-75">Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="p-3 rounded" style="background-color: #d1fae5;">
                                    <div class="fs-4 fw-bold text-success">{{ $rencanaSelesai }}</div>
                                    <small class="text-muted">Valid</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background-color: #fef3c7;">
                                    <div class="fs-4 fw-bold text-warning">{{ $rencanaProgress }}</div>
                                    <small class="text-muted">Tidak Valid</small>
                                </div>
                            </div>
                        </div>

                        @php
                            $progressPercentage = ($rencanaSelesai / max($totalRencanaKerja,1)) * 100;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted fw-semibold">Progress Keseluruhan</small>
                                <small class="fw-bold text-primary">{{ number_format($progressPercentage, 1) }}%</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                     style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>

                        <a href="{{route('rencanakerja')}}" class="btn btn-primary w-100">
                            <i class="fas fa-list me-2"></i> Lihat Semua Rencana Kerja
                        </a>
                    </div>
                </div>
            </div>

            <!-- Monitoring Evaluasi Detail -->
            <div class="col-lg-6">
                <div class="card detail-card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-bold">
                                    <i class="fas fa-chart-line me-2"></i> Monitoring Evaluasi
                                </h5>
                                <small class="opacity-75">Status monitoring & evaluasi</small>
                            </div>
                            <div class="text-end">
                                <div class="fs-3 fw-bold">{{ $totalMonev }}</div>
                                <small class="opacity-75">Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="p-3 rounded" style="background-color: #d1fae5;">
                                    <div class="fs-4 fw-bold text-success">{{ $monevLengkap }}</div>
                                    <small class="text-muted">Data Lengkap</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded position-relative" style="background-color: #fee2e2;">
                                    <div class="fs-4 fw-bold text-danger">{{ $monevBelumLengkap }}</div>
                                    <small class="text-muted">Belum Lengkap</small>
                                    @if($monevBelumLengkap > 0)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-pill pulse-animation">!</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($monevBelumLengkap > 0)
                        <div class="alert alert-warning border-0 mb-3" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                <div>
                                    <strong>Perhatian!</strong><br>
                                    <small>{{ $monevBelumLengkap }} data monev belum terisi lengkap</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        @php
                            $monevProgressPercentage = ($monevLengkap / max($totalMonev,1)) * 100;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted fw-semibold">Kelengkapan Data</small>
                                <small class="fw-bold text-info">{{ number_format($monevProgressPercentage, 1) }}%</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated"
                                     style="width: {{ $monevProgressPercentage }}%"></div>
                            </div>
                        </div>

                        <a href="{{route('monev')}}" class="btn btn-info w-100 text-white">
                            <i class="fas fa-edit me-2"></i> Lengkapi Data Monitoring
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
