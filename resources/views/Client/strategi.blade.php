@extends('componentsClient.layout')

@section('content')
    <div class="container-fluid py-4">
        {{-- Navbar --}}
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Strategi</li>
                    </ol>
                    {{-- <h6 class="font-weight-bolder text-white mb-0">Strategi</h6> --}}
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                id="liveSearchInput">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">

                        <li class="nav-item d-flex align-items-center">
                            <a href="{{ route('login') }}" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
        </nav>

        {{-- Konten --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4 shadow">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Daftar Strategi</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">PROGRAM</th>
                                        <th class="text-center">STRATEGI</th>
                                        <th class="text-center">KETERANGAN</th>
                                    </tr>
                                </thead>

                                <tbody id="dataTabelBody">

                                    @foreach ($strategi as $data)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $data->program }}</td>
                                            <td >{{ $data->subprogram }}</td>
                                            <td class="text-center">{{ $data->uraian }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- area pagination di luar scroll --}}
                        <div class="d-flex justify-content-between align-items-center px-3 mt-3 flex-wrap">
                            <div id="infoContainer" class="text-secondary small"></div>
                            <div id="paginationContainer" class="ms-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/progres-tabel.js') }}"></script>
@endpush
