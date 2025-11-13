@extends('componentsClient.layout')

@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Rencana Aksi</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Rencana Aksi</h6>
                </nav>

                <div class="ms-md-auto pe-md-1 d-flex align-items-center">
                    <div class="input-group">
                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
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
                </ul>
            </div>
        </nav>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Daftar Rencana Aksi</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                           <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">STRATEGI</th>
                                        <th>RENCANA AKSI / AKTIVITAS</th>
                                        <th>SUB KEGIATAN</th>
                                        <th>KEGIATAN</th>
                                        <th>PROGRAM</th>
                                        <th>LOKASI</th>
                                        <th class="text-center">VOLUME TARGET</th>
                                        <th class="text-center">SATUAN</th>
                                        <th class="text-center">TAHUN</th>
                                        <th>PERANGKAT DAERAH</th>
                                        <th>ANGGARAN</th>
                                        <th>SUMBERDANA</th>
                                        <th>KETERANGAN</th>

                                    </tr>
                                </thead>
                                <tbody id="rencana-aksi-table-body">
                                    @forelse ($rencanaAksi as $index => $item)
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

                                            @if (count($anggarans) > 1)
                                                <td class="multi-item-rensi align-middle">
                                                    @foreach ($anggarans as $anggaran)
                                                        <div>{{ $anggaran ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="align-middle">{{ $item->anggaran ?: '-' }}</td>
                                            @endif

                                            @if (count($sumberdanas) > 1)
                                                <td class="multi-item-rensi align-middle">
                                                    @foreach ($sumberdanas as $sumber)
                                                        <div>{{ $sumber ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td class="align-middle">{{ $item->sumberdana ?: '-' }}</td>
                                            @endif
                                            <td>{{ $item->keterangan ?? '-' }}</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center text-muted">Belum ada data Rencana Aksi.
                                            </td>
                                        </tr>
                                        
                                    @endforelse
                                </tbody>
                            </table>
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
