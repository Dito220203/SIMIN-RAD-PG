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
    {{-- <link href="{{ asset('client/fonts/font.css') }}" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- <link href="{{ asset('client/css/all.min.css') }}" rel="stylesheet"> --}}
    <link id="pagestyle" href="{{ asset('client/css/argon-dashboard.css?v=2') }}" rel="stylesheet" />
    <link rel="stylesheet" href=" {{ asset('client/css/leaflet.css') }}" />
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

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="overflow-y: auto;" >
        @yield('content')


    </main>



    <!--   preload JS Files   -->
    <script src="{{ asset('client/js/preload.js') }}"></script>
    {{-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> --}}
    <script src="{{ asset('client/js/leaflet.js') }}"></script>
    <script src="{{ asset('js/live-search.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> --}}
    <script src="{{ asset('client/js/jquery-3.7.0.js') }}"></script>
    {{-- File JS Bootstrap Anda (sudah ada) --}}
    {{-- <script src="https." ...></script> --}}

    {{-- TAMBAHKAN INI: DataTables Core JS dan JS untuk Bootstrap 5 --}}
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="{{ asset('client/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('client/js/dataTables.bootstrap5.min.js') }}"></script>

    <!--   Core JS Files   -->
    <script src="{{ asset('client/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('client/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('client/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('client/js/sweetalert2@11.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
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
    <script async defer src="{{ asset('client/js/buttons.js') }}"></script>
    <script src="{{ asset('client/js/argon-dashboard.min.js?v=2.1.0') }}"></script>
    <script src="{{ asset('js/sidenav-toggle.js') }}"></script>
    @stack('scripts')
</body>

</html>
