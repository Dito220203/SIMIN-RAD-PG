@extends('components.layout')

@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Rencana Aksi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Rencana Aksi</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- BARIS UTAMA ATAS --}}
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">

                                {{-- KOLOM KIRI: TOMBOL TAMBAH DAN EXPORT --}}
                                <div class="gap-2">
                                    @if (Auth::guard('pengguna')->user()->level === 'Super Admin')
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <a href="{{ route('rencanaAksi.create') }}" class="btn btn-tambah-utama" data-turbo="false">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Tambah Rencana Aksi
                                            </a>
                                            <a href="{{ route('rencanaAksi.export.excel', request()->query()) }}"
                                                class="btn btn-success" data-turbo="false">
                                                <i class="fa-solid fa-file-excel me-1"></i>
                                                Export Excel
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                {{-- KOLOM KANAN: ENTRIES, FILTER TAHUN, SEARCHING --}}
                                {{-- Menggunakan flex-wrap untuk responsif dan menempatkan setiap elemen di div-nya sendiri --}}


                                    <div class="d-flex align-items-center gap-2">
                                    <label for="showEntries">Tampilkan</label>
                                    <select id="showEntries" class="form-select form-select-sm" style="width: auto;">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span>entri</span>
                                </div>

                                    {{-- 2. DIV FILTER TAHUN --}}
                                    <form id="filter-form" method="GET" class="d-flex flex-column flex-md-row gap-2">
                                        <div class="input-group w-auto">
                                            <label class="input-group-text" for="tahun-filter">
                                                <i class="fas fa-calendar-alt"></i>
                                            </label>
                                            <select name="tahun" id="tahun-filter" class="form-select"
                                                style="width: 150px;">
                                                <option value="">Semua Tahun</option>
                                                @foreach ($tahuns as $tahun)
                                                    <option value="{{ $tahun }}"
                                                        {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                         {{-- Form pencarian yang sudah ada --}}
                                    <div class="input-group w-auto">
                                        <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                            id="liveSearchInput">

                                    </div>
                                    </form>





                                @push('scripts')
                                    <script>
                                        $(document).ready(function() {
                                            // "Dengarkan" setiap ada perubahan pada dropdown tahun
                                            $('#tahun-filter').on('change', function() {
                                                // Jika ada perubahan, langsung submit form-nya secara otomatis
                                                $('#filter-form').submit();
                                            });
                                        });
                                    </script>
                                @endpush

                            </div> {{-- Akhir dari div.d-flex.flex-column.flex-md-row (baris utama) --}}

                            <div class="table-container">
                                <div class="top-scrollbar-container">
                                    <div class="top-scrollbar-content"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="detail-table" id="dataTable" style="min-width: 2500px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">No</th>
                                                <th class="text-center" style="width: 200px;">Strategi</th>
                                                <th style="width: 300px;">Rencana Aksi / Aktivitas</th>
                                                <th style="width: 350px;">Sub Kegiatan</th>
                                                <th style="width: 250px;">Kegiatan</th>
                                                <th style="width: 300px;">Program</th>
                                                <th style="width: 150px;">Lokasi</th>
                                                <th style="width: 100px;">Volume Target</th>
                                                <th style="width: 100px;">Satuan</th>
                                                <th style="width: 100px;">Tahun</th>
                                                <th style="width: 300px;">Perangkat Daerah</th>
                                                <th style="width: 300px;">Anggaran</th>
                                                <th style="width: 150px;">Sumber Dana</th>
                                                <th style="width: 300px;">Keterangan</th>

                                                @if (Auth::guard('pengguna')->user()->level === 'Super Admin')
                                                    <th style="width: 120px;">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="dataTabelBody">
                                            @foreach ($rencanaAksi as $data)
                                                <tr>
                                                    <td class="text-center">{{ $loop->index }}
                                                    </td>
                                                    <td class="text-center">{{ $data->subprogram->subprogram ?? '-' }}</td>
                                                    <td>{{ $data->rencana_aksi }}</td>
                                                    <td>{{ $data->sub_kegiatan }}</td>
                                                    <td>{{ $data->kegiatan }}</td>
                                                    <td>{{ $data->nama_program }}</td>
                                                    <td>{{ $data->lokasi }}</td>
                                                    <td>{{ $data->volume }}</td>
                                                    <td>{{ $data->satuan }}</td>
                                                    <td>{{ $data->tahun }}</td>
                                                    <td>{{ $data->opd->nama ?? '-' }}</td>

                                                    @php
                                                        $anggarans = explode('; ', $data->anggaran);
                                                        $sumberdanas = explode('; ', $data->sumberdana);
                                                    @endphp

                                                    @if (count($anggarans) > 1)
                                                        <td class="multi-item-rensi align-middle">
                                                            @foreach ($anggarans as $anggaran)
                                                                <div>{{ $anggaran ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        <td class="align-middle">{{ $data->anggaran ?: '-' }}</td>
                                                    @endif

                                                    @if (count($sumberdanas) > 1)
                                                        <td class="multi-item-rensi align-middle">
                                                            @foreach ($sumberdanas as $sumber)
                                                                <div>{{ $sumber ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        <td class="align-middle">{{ $data->sumberdana ?: '-' }}</td>
                                                    @endif

                                                    <td>{{ $data->keterangan ?? '-' }}</td>
                                                    @if (Auth::guard('pengguna')->user()->level === 'Super Admin')
                                                        <td>
                                                            <div class="d-flex justify-content-center gap-1" data-turbo="false">
                                                                <a href="{{ route('rencanaAksi.edit', $data->id) }}"
                                                                    class="btn btn-tambah-utama btn-sm" title="Edit">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </a>
                                                                <form id="formDelete-{{ $data->id }}"
                                                                    action="{{ route('rencanaAksi.destroy', $data->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        onclick="confirmDelete('{{ $data->id }}')">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                                    <div id="paginationInfo"></div>
                                    <div id="paginationControls"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
