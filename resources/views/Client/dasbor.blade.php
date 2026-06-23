@extends('componentsClient.layout')

@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Home</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            {{-- <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                id="liveSearchInput"> --}}
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('login') }}" class="nav-link text-white font-weight-bold px-0 ms-3">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                    </ul>
                </div>
        </nav>

        <!-- SECTION 1: WELCOME BANNER (IDENTITAS WEBSITE) -->
        <div class="row mt-4 mb-4">
            <div class="col-lg-12">
                <div class="card card-welcome-banner border-radius-xl overflow-hidden position-relative">

                    <!-- 1. BACKGROUND CAROUSEL (DARI DATABASE) -->
                    <!-- Kita tambahkan ID 'Carousel' untuk di-target Javascript nanti -->
                    <div id="Carousel"
                        class="carousel slide position-absolute w-100 h-100 welcome-carousel-wrapper"
                        data-bs-ride="carousel" data-bs-interval="5000">
                        <div class="carousel-inner h-100">
                            @foreach ($banners as $index => $banner)
                                <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                                    <!-- Gambar Latar -->
                                    <div class="carousel-bg-image w-100 h-100"
                                        style="background-image: url('{{ asset('storage/' . $banner->file) }}');"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. OVERLAY GELAP AGAR TEKS SELALU TERBACA -->
                    <div class="carousel-overlay"></div>

                    <!-- 3. TEKS IDENTITAS & LOGO (TETAP DIAM) -->
                    <div
                        class="card-body p-4 w-100 h-100 d-flex flex-column justify-content-center position-relative welcome-content">
                        <div class="row w-100 mb-5"> <!-- Tambah margin-bottom agar tidak nabrak indikator -->
                            <div class="col-lg-9 my-auto">
                                <h2 class="text-white font-weight-bolder mb-3 text-shadow-custom">Monitoring & Evaluasi
                                    RAD-PG Lumajang</h2>
                                <p class="text-white mb-0 text-shadow-custom welcome-text-desc">
                                    Selamat datang di Sistem Rencana Aksi Daerah Pangan dan Gizi.
                                    Kelola data rencana kerja, pantau progres kegiatan, dan lakukan monitoring evaluasi
                                    dalam satu dashboard terintegrasi untuk Kabupaten Lumajang yang lebih sejahtera.
                                </p>
                            </div>
                            <!-- Logo Lumajang -->
                            <div class="col-lg-3 text-end d-none d-lg-block my-auto">
                                <img src="{{ asset('assets/img/logo kabupaten.png') }}" class="img-fluid welcome-logo"
                                    alt="Logo Lumajang">
                            </div>
                        </div>
                    </div>

                    <!-- 4. CUSTOM PROGRESS BAR INDICATOR (MODEL PERTAMINA) -->
                    <!-- Posisinya absolute di bawah card -->
                    <div class="custom-indicators-container">
                        @foreach ($banners as $index => $banner)
                            <div class="indicator-wrapper" data-bs-target="#Carousel"
                                data-bs-slide-to="{{ $index }}">
                                <!-- Judul Banner (Opsional, hapus kalau tidak perlu) -->
                                <span class="indicator-title {{ $index == 0 ? 'active' : '' }}">{{ $banner->judul }}</span>
                                <!-- Track Progres Bar -->
                                <div class="progress-track">
                                    <div class="progress-fill {{ $index == 0 ? 'active' : '' }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <!-- ========================================== -->

        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Rencana Aksi</p>
                                    <h5 class="font-weight-bolder">{{ $rencanaAksi }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-icon shadow-primary text-center rounded-circle">
                                    <i class="fa-solid fa-list-check text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Rencana Kerja</p>
                                    <h5 class="font-weight-bolder">{{ $rencanaKerja }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-icon shadow-danger text-center rounded-circle">
                                    <i class="fa-solid fa-briefcase text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Monitoring Evaluasi</p>
                                    <h5 class="font-weight-bolder">{{ $Monev }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-icon shadow-success text-center rounded-circle">
                                    <i class="fa-solid fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Progres Kegiatan</p>
                                    <h5 class="font-weight-bolder">{{ $totalProgresCount }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-icon shadow-warning text-center rounded-circle">
                                    <i class="fa-solid fa-bars-progress text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <!-- Ubah col-lg-7 menjadi col-lg-12 agar grafiknya memanjang penuh -->
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Progres Kerja Per Tahun</h6>
                        <select id="filterTahun" class="form-select" style="width:150px;">
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <!-- Tinggikan sedikit canvas chart karena sekarang lebih lebar -->
                            <canvas id="chart-line" class="chart-canvas" height="350"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection

@push('scripts')
    <script>
        // === 1. CHART CONFIGURATION ===
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, "rgba(94,114,228,0.2)");
        gradientStroke1.addColorStop(0.2, "rgba(94,114,228,0.0)");
        gradientStroke1.addColorStop(0, "rgba(94,114,228,0)");

        var dataByYear = @json($dataByYear);
        var tahunDefault = "{{ date('Y') }}";

        var chartLine = new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Jumlah Progres",
                    borderColor: "#718355",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    data: dataByYear[tahunDefault] ?? [],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
            }
        });

        // Filter Tahun Logic
        document.getElementById("filterTahun").addEventListener("change", function() {
            let tahun = this.value;
            chartLine.data.datasets[0].data = dataByYear[tahun] ?? [];
            chartLine.update();
        });

        // === 2. PERTAMINA CAROUSEL INDICATOR LOGIC ===
        var Carousel = document.getElementById('Carousel');

        if (Carousel) {
            // Event listener bawaan Bootstrap: Setiap kali gambar mulai berganti
            Carousel.addEventListener('slide.bs.carousel', function(e) {

                // 1. Ambil index gambar yang akan aktif selanjutnya
                var nextSlideIndex = e.to;

                // 2. Ambil semua elemen teks judul & progress bar
                var titles = document.querySelectorAll('.indicator-title');
                var fills = document.querySelectorAll('.progress-fill');

                // 3. Matikan class 'active' di SEMUA elemen dulu
                titles.forEach(t => t.classList.remove('active'));
                fills.forEach(f => {
                    f.classList.remove('active');
                    // Trik khusus untuk mereset animasi CSS
                    f.style.animation = 'none';
                    f.offsetHeight; // Trigger reflow
                    f.style.animation = null;
                });

                // 4. Nyalakan class 'active' HANYA pada elemen yang sesuai urutan gambar baru
                titles[nextSlideIndex].classList.add('active');
                fills[nextSlideIndex].classList.add('active');
            });
        }
    </script>
@endpush
