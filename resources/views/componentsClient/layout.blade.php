<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../client/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../client/img/favicon.png">
    <title>DASHBOARD RENCANA AKSI DAERAH PANGAN DAN GIZI (RAD-PG)</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />

    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('client/css/argon-dashboard.css?v=2') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- File CSS Bootstrap Anda (sudah ada) --}}
    <link href="https." rel="stylesheet" ...>

    {{-- TAMBAHKAN INI: DataTables CSS untuk Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="{{ asset('client/css/style-tambahan.css') }}" rel="stylesheet" />
</head>



<body class="g-sidenav-show ">
    {{-- preload --}}
    <div id="preloader">
        <div class="dots-container">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <div class="min-height-250 bg-menu position-absolute w-100 "></div>
    @include('componentsClient.sidebar')
    {{-- @include('componentsClient.navbar') --}}

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="overflow-y: auto;">

        <!-- Navbar -->

        <!-- End Navbar -->
        @yield('content')


    </main>



    <!--   preload JS Files   -->
    <script src="{{ asset('client/js/preload.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/live-search.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    {{-- File JS Bootstrap Anda (sudah ada) --}}
    <script src="https." ...></script>

    {{-- TAMBAHKAN INI: DataTables Core JS dan JS untuk Bootstrap 5 --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!--   Core JS Files   -->
    <script src="{{ asset('client/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('client/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/chartjs.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/alerthapus.js') }}"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
        @php
            session()->forget('success');
        @endphp
    @endif


    <!-- Github buttons -->
    <script async defer src="{{ asset('client/js/buttons.js') }}"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('client/js/argon-dashboard.min.js?v=2.1.0') }}"></script>
    <script src="{{ asset('js/sidenav-toggle.js') }}"></script>
    @stack('scripts')
</body>

</html>
