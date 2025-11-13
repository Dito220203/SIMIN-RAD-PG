@extends('componentsClient.layout')

@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Rencana Kegiatan</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Rencana Kegiatan</h6>
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
                        <h6>Daftar Rencana Kerja</h6>
                        <div id="lengthContainer"></div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="tabelSaya" class="table align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Strategi</th>
                                        <th>Rencana Aksi / Aktivitas</th>
                                        <th>Sub Kegiatan</th>
                                        <th>Kegiatan</th>
                                        <th>Program</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Volume Target</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Tahun</th>
                                        <th>Perangkat Daerah</th>
                                        <th>Anggaran</th>
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
@endpush
