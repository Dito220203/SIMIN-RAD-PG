@extends('componentsClient.layout')
@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Progres Kegiatan</li>
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
                        <h6>Daftar Progres Kegiatan</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="top-scrollbar-wrapper">
                            <div class="top-scrollbar-content"></div>
                        </div>
                        <div class="table-responsive p-0">
                           <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">RENCANA AKSI / AKTIVITAS</th>
                                        <th class="text-center">TAHUN</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">DOKUMENTASI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($progres as $data)
                                        <tr id="row-{{ $data->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{-- Panggil relasi 'rencanakerja', lalu kolom 'rencana_aksi' dari tabel RencanaKerja --}}
                                                {{ $data->monev?->rencanakerja?->rencana_aksi ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ $data->monev->rencanakerja->tahun ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($data->status === 'Valid')
                                                    <span class="badge bg-success">{{ $data->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $data->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">

                                                <!-- Tombol Detail -->
                                                <button type="button" class="btn btn-info btn-sm m-0" title="Lihat"
                                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}">
                                                    lihat
                                                </button>



                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @foreach ($progres as $data)
                                <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1"
                                    aria-labelledby="detailModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-super-xl modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel{{ $data->id }}">
                                                    <i class="bi bi-info-circle me-2"></i>Detail Progres
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-4">

                                                    <div class="col-md-6">
                                                        <div>
                                                            <h6 class="mb-3 fw-bold"><i
                                                                    class="bi bi-list-ul text-primary me-2"></i>Informasi
                                                                Detail</h6>
                                                            <div class="table-wrapper">
                                                                <table class="table table-hover mb-0">
                                                                    <tr>
                                                                        <th class="bg-light th-detail-lebar"><i
                                                                                class="bi bi-clipboard-check me-2"></i>Rencana
                                                                            Aksi</th>
                                                                        <td class="fw-medium">
                                                                            {{ $data->monev?->rencanakerja?->rencana_aksi ?? '-' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light"><i
                                                                                class="bi bi-calendar me-2"></i>Tahun
                                                                        </th>
                                                                        <td>{{ $data->monev->tahun ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="bg-light"><i
                                                                                class="bi bi-flag me-2"></i>Status</th>
                                                                        <td><span
                                                                                class="badge {{ $data->status === 'Valid' ? 'bg-success' : 'bg-secondary' }}">{{ $data->status }}</span>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="bg-light"><i
                                                                                class="bi bi-card-text me-2"></i>Uraian
                                                                        </th>
                                                                        <td>
                                                                            <div class="keterangan-panjang">
                                                                                @if ($data->monev && $data->monev->fotoProgres->isNotEmpty())
                                                                                    {{ $data->monev->fotoProgres->first()->deskripsi ?? 'Tidak ada uraian.' }}
                                                                                @else
                                                                                    <span
                                                                                        class="text-muted fst-italic">Tidak
                                                                                        ada uraian</span>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>



                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4">
                                                            <h6 class="mb-3 fw-bold"><i
                                                                    class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi
                                                                Peta</h6>
                                                            @if ($data->monev && $data->monev->map)
                                                                <div id="detailMapProgres{{ $data->id }}"
                                                                    class="peta-container rounded shadow-sm"
                                                                    data-latitude="{{ $data->monev->map->latitude }}"
                                                                    data-longitude="{{ $data->monev->map->longitude }}">
                                                                </div>
                                                            @else
                                                                <div class="alert alert-light placeholder-container">
                                                                    <i class="bi bi-map placeholder-icon"></i>
                                                                    <p class="mb-0 mt-3 text-muted">Lokasi belum
                                                                        ditandai</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <h6 class="mb-3 fw-bold"><i
                                                                class="bi bi-images text-primary me-2"></i>Dokumentasi
                                                            Foto</h6>
                                                        <div class="foto-container-scrollable">
                                                            @if ($data->monev && $data->monev->fotoProgres->isNotEmpty())
                                                                <div class="row g-3">
                                                                    @foreach ($data->monev->fotoProgres as $foto)
                                                                        <div class="col-12">
                                                                            <a href="{{ asset('storage/' . $foto->foto) }}"
                                                                                target="_blank"
                                                                                class="d-block hover-effect">
                                                                                <img src="{{ asset('storage/' . $foto->foto) }}"
                                                                                    alt="Foto Progres"
                                                                                    class="galeri-foto-item">
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="alert alert-light text-center m-0 placeholder-container">
                                                                    <i class="bi bi-image placeholder-icon"></i>
                                                                    <p class="mb-0 mt-2 text-muted">Belum ada foto</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-2"></i>Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
