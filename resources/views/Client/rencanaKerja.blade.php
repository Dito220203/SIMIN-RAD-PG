@extends('componentsClient.layout')

@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Rencana Kegiatan</li>
                    </ol>
                    {{-- <h6 class="font-weight-bolder text-white mb-0">Rencana Kegiatan</h6> --}}
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
                        <h6>Daftar Rencana Kerja</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body ">
                        <div class="top-scrollbar-wrapper">
                            <div class="top-scrollbar-content"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Strategi</th>
                                        <th class="kolom-panjang">RENCANA AKSI / AKTIVITAS</th>
                                        <th class="kolom-panjang">SUB KEGIATAN</th>
                                        <th class="kolom-panjang">KEGIATAN</th>
                                        <th class="kolom-panjang">PROGRAM</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Volume Target</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Tahun</th>
                                        <th>Perangkat Daerah</th>
                                        {{-- <th>Anggaran</th> --}}
                                        <th>Sumber Dana</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>

                                    </tr>
                                </thead>
                                <tbody id="rencana-kerja-table-body">
                                    @forelse ($rencanaKerja as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->subprogram->subprogram ?? '-' }}</td>
                                            <td>{{ $item->rencana_aksi }}</td>
                                            <td>{{ $item->sub_kegiatan }}</td>
                                            <td>{{ $item->kegiatan }}</td>
                                            <td>{{ $item->nama_program }}</td>
                                            <td>{{ $item->lokasi }}</td>
                                            <td class="text-center">{{ $item->volume }}</td>
                                            <td class="text-center">{{ $item->satuan }}</td>
                                            <td class="text-center">{{ $item->tahun }}</td>
                                            <td>{{ $item->opd->nama ?? '-' }}</td>
                                            @php
                                                $anggarans = explode('; ', $item->anggaran);
                                                $sumberdanas = explode('; ', $item->sumberdana);
                                            @endphp

                                            {{-- @if (count($anggarans) > 1)
                                                <td class="multi-item-rensi align-middle">
                                                    @foreach ($anggarans as $anggaran)
                                                        <div>{{ $anggaran ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="align-middle">{{ $item->anggaran ?: '-' }}</td>
                                            @endif --}}

                                            @if (count($sumberdanas) > 1)
                                                <td class="multi-item-rensi align-middle">
                                                    @foreach ($sumberdanas as $sumber)
                                                        <div>{{ $sumber ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="align-middle">{{ $item->sumberdana ?: '-' }}</td>
                                            @endif

                                            <td class="text-center">
                                                @if ($item->status === 'Valid')
                                                    <span class="badge bg-success">{{ $item->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->keterangan ?? '-' }}</td>

                                        </tr>
                                    @empty
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
    {{-- <script src="{{ asset('js/progres-tabel.js') }}"></script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Temukan semua elemen
            const topScroller = document.querySelector(".top-scrollbar-wrapper");
            const topContent = document.querySelector(".top-scrollbar-content");
            const bottomScroller = document.querySelector(".table-responsive");
            const mainTable = document.querySelector("#tabelSaya");

            // Pastikan semua elemen ada
            if (topScroller && bottomScroller && mainTable) {

                // Fungsi untuk mengatur lebar scrollbar atas
                function setTopScrollWidth() {
                    topContent.style.width = mainTable.scrollWidth + 'px';
                }

                // 2. Set lebar awal
                setTopScrollWidth();

                // 3. Sinkronisasi scroll dari ATAS ke BAWAH
                topScroller.addEventListener("scroll", function() {
                    bottomScroller.scrollLeft = topScroller.scrollLeft;
                });

                // 4. Sinkronisasi scroll dari BAWAH ke ATAS
                bottomScroller.addEventListener("scroll", function() {
                    topScroller.scrollLeft = bottomScroller.scrollLeft;
                });

                // 5. (PENTING) Update lebar jika tabel berubah
                // Ini untuk jaga-jaga jika progres-tabel.js mengubah lebar tabel
                const observer = new ResizeObserver(entries => {
                    for (let entry of entries) {
                        setTopScrollWidth();
                    }
                });
                observer.observe(mainTable);
            }
        });
    </script>
@endpush
