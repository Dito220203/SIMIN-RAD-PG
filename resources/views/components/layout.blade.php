<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>admin simin rad-pg</title>
    <meta name="description" content="simin rad-pg lumajang">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link href="{{ asset('assets/css/googlefonts.css') }}" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <!-- PETA -->
    <link href="{{ asset('assets/vendor/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/leaflet/geosearch.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" /> --}}

    <link href="{{ asset('assets/vendor/quil1.3.6/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quil1.3.6/quill.snow.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('assets/vendor/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>


</head>

<style>
    html,
    body {
        height: 100%;
        margin: 0;
        font-family: "nunito", sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    .wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    main.main {
        flex: 1;
    }

    footer.footer {
        margin-top: auto;
    }

    /* Styling untuk tombol saat loading */
    .btn.is-loading .spinner-border {
        margin-right: 0.5rem;
    }
    /* Mengubah background header menjadi HIJAU TUA */
#header {
  background-color: #2e473a; /* <-- Warna hijau tua yang baru */
}

/* Mengubah warna teks & ikon di header menjadi putih agar terbaca */
#header .toggle-sidebar-btn,
#header .logo span,
#header .nav-link {
  color: #FFFFFF;
}

/* Memastikan ikon di dalam nav-link juga berubah */
#header .nav-link i {
  color: #FFFFFF;
}



</style>



{{-- <body> --}}

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('components.sidebar')
        @include('components.navbar')
        <main class="main">
            @yield('content')
        </main>

        {{-- modal ganti pasword --}}
        @include('admin.GantiPassword.index')

    </div>


    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Rad-PG</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    {{-- js buatan --}}
    <script src="{{ asset('js/preview-image.js') }}"></script>

    <!-- Body (before closing body tag) -->
    <script src="{{ asset('assets/vendor/quil1.3.6/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery371/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/1.13.6/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/1.13.6/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert-custom.js') }}"></script>
    <script src="{{ asset('js/alerthapus2.js') }}"></script>
    <script src="{{ asset('js/updateStatus.js') }}"></script>
    <script src="{{ asset('js/entries.js') }}"></script>
    <script src="{{ asset('js/loadingSubmit.js') }}"></script>


@stack('scripts') <!-- pastikan ini tetap ada -->

    {{-- <script src="{{ asset('js/live-search.js') }}"></script> --}}
    <script src="{{ asset('js/modalApludMonevDokumentasi.js') }}"></script>
    <script src="{{ asset('assets/vendor/leaflet/geosearch.umd.js') }}"></script>
@push('scripts')
    {{-- PERUBAHAN: Ganti progres-tabel.js dengan entries-pagnation-admin.js --}}
    <script src="{{ asset('js/entries-pagnation-admin.js') }}"></script>

@endpush

    <!-- SweetAlert Success -->
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

    @stack('scripts')

</body>

</html>
