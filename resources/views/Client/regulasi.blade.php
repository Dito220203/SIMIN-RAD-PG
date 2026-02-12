@extends('componentsClient.layout')
@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Regulasi</li>
                    </ol>
                    {{-- <h6 class="font-weight-bolder text-white mb-0">Rencana Aksi</h6> --}}
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

        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Daftar Regulasi</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="top-scrollbar-wrapper">
                            <div class="top-scrollbar-content"></div>
                        </div>
                        <div class="table-responsive p-0">
                            <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="kolom-panjang">JUDUL</th>
                                        <th class="text-center">TANGGAL</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">FILE</th>
                                    </tr>
                                </thead>
                                <tbody id="rencana-aksi-table-body">
                                    @forelse ($regulasi as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->judul }}</td>
                                            <td class="text-center">{{ $item->tanggal }}</td>
                                            <td class="text-center">
                                                @if ($item->status === 'Aktif')
                                                    <span class="badge bg-success">{{ $item->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle">

                                                <button type="button" class="btn btn-info btn-sm m-0"
                                                    data-bs-toggle="modal" data-bs-target="#fileModal{{ $item->id }}">
                                                    Lihat
                                                </button>

                                                <div class="modal fade" id="fileModal{{ $item->id }}" tabindex="-1"
                                                    aria-labelledby="fileModalLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="fileModalLabel{{ $item->id }}">
                                                                    Lihat File Regulasi
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <iframe
                                                                    src="{{ asset('storage/regulasi/' . $item->file) }}"
                                                                    width="100%" height="600px"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada regulasi ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
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
