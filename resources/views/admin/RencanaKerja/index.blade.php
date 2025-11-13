@extends('components.layout')
@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Rencana Kerja</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Rencana Kerja</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Header tools -->
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">
                                <div class="gap-2">
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <a href="{{ route('rencana.create') }}" class="btn btn-tambah-utama">
                                            + Tambah Rencana
                                        </a>

                                        {{-- BARU --}}
                                        <a href="{{ route('rencana.export.excel', request()->query()) }}"
                                            class="btn btn-success">
                                            <i class="fa-solid fa-file-excel"></i> Export Excel
                                        </a>
                                    </div>
                                </div>

                                {{-- UBAH FORM MENJADI SEPERTI INI --}}
                                <form method="GET" class="d-flex flex-column flex-md-row gap-2">
                                    {{-- TAMBAHKAN DROPDOWN FILTER TAHUN DI SINI --}}
                                    <div class="input-group w-auto">
                                        <label class="input-group-text" for="tahun-filter">
                                            <i class="fas fa-calendar-alt"></i>
                                        </label>
                                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                                            <option value="">Semua Tahun</option>
                                            @foreach ($daftarTahun as $thn)
                                                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                                    {{ $thn }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Form pencarian yang sudah ada --}}
                                    <div class="input-group w-auto">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Data..."
                                            value="{{ request('search') }}">
                                        <button class="btn-tambah-utama" type="submit">Cari</button>
                                    </div>

                                    {{-- Tombol Reset untuk membersihkan semua filter --}}
                                    @if (request('search') || request('tahun'))
                                        <a href="{{ route('rencanakerja') }}" class="btn btn-secondary"> <i
                                                class="fas fa-sync-alt"></i></a>
                                    @endif
                                </form>
                            </div>
                            {{-- --- aksi buka kunci --- --}}
                            @if (Auth::guard('pengguna')->user()->level === 'Super Admin' && isset($allOpds) && $allOpds->isNotEmpty())
                                <div class="card-body border-top pt-3">
                                    <h5 class="card-title" style="padding: 0 !important; margin-bottom: 5px;">Aksi Kunci
                                        Data per OPD</h5>
                                    <form action="{{ route('renja.bulk-lock') }}" method="POST" id="bulk-lock-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-2 align-items-end">
                                            {{-- Dropdown Pilih OPD --}}
                                            <div class="col-md-5">
                                                <label for="opd_id_filter" class="form-label">Perangkat Daerah</label>
                                                <select name="opd_id" id="opd_id_filter" class="form-select form-select-sm"
                                                    required>
                                                    <option value="" selected disabled>-- Pilih Perangkat Daerah --
                                                    </option>
                                                    @foreach ($allOpds as $opd)
                                                        <option value="{{ $opd->id }}">{{ $opd->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Dropdown Pilih Aksi --}}
                                            <div class="col-md-4">
                                                <label for="action_filter" class="form-label">Aksi</label>
                                                <select name="action" id="action_filter" class="form-select form-select-sm"
                                                    required>
                                                    <option value="" selected disabled>-- Pilih Aksi --</option>
                                                    <option value="lock">Kunci Semua Data</option>
                                                    <option value="unlock">Buka Semua Kunci</option>
                                                </select>
                                            </div>

                                            {{-- Tombol Terapkan --}}
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-play me-1"></i> Terapkan Aksi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif


                            <!-- Table -->
                            <div class="table-container">
                                <div class="top-scrollbar-container">
                                    <div class="top-scrollbar-content"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="detail-table" id="TableRencanaAksi" style="min-width: 2500px;">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;">No</th>
                                                <th class="text-center" style="width: 200px;">Strategi</th>
                                                <th class="text-center" style="width: 300px;">Rencana Aksi/Aktivitas</th>
                                                <th class="text-center" style="width: 350px;">Sub Kegiatan</th>
                                                <th class="text-center" style="width: 250px;">Kegiatan</th>
                                                <th class="text-center" style="width: 300px;">Program</th>
                                                <th class="text-center" style="width: 200px;">Lokasi</th>
                                                <th class="text-center" style="width: 100px;">Volume Target</th>
                                                <th class="text-center" style="width: 100px;">Satuan</th>
                                                <th class="text-center" style="width: 100px;">Tahun</th>
                                                <th class="text-center" style="width: 200px;">Perangkat Daerah</th>
                                                <th class="text-center" style="width: 300px;">Anggaran</th>
                                                <th class="text-center" style="width: 200px;">Sumber Dana</th>
                                                <th class="text-center" style="width: 100px;">Status</th>
                                                <th class="text-center" style="width: 300px;">Keterangan</th>
                                                <th class="text-center" style="width: 120px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rencana as $data)
                                                <tr id="row-{{ $data->id }}"
                                                    class="{{ $data->input === 'manual' ? 'highlight-manual-renja' : '' }}">

                                                    <td class="text-center">{{ $rencana->firstItem() + $loop->index }}
                                                    </td>
                                                    <td class="text-center">{{ $data->subprogram->subprogram ?? '-' }}
                                                    </td>
                                                    <td>{{ $data->rencana_aksi }}</td>
                                                    <td>{{ $data->sub_kegiatan }}</td>
                                                    <td>{{ $data->kegiatan }}</td>
                                                    <td>{{ $data->nama_program }}</td>
                                                    <td>{{ $data->lokasi }}</td>
                                                    <td class="text-center">{{ $data->volume }}</td>
                                                    <td class="text-center">{{ $data->satuan }}</td>
                                                    <td class="text-center">{{ $data->tahun }}</td>
                                                    <td class="text-center">{{ $data->opd->nama ?? '-' }}</td>
                                                    @php
                                                        $anggarans = explode('; ', $data->anggaran);
                                                        $sumberdanas = explode('; ', $data->sumberdana);
                                                    @endphp

                                                    {{-- Cek untuk Kolom Anggaran --}}
                                                    @if (count($anggarans) > 1)
                                                        {{-- Jika data lebih dari satu, gunakan tampilan multi-baris --}}
                                                        <td class="multi-item-renja align-middle">
                                                            @foreach ($anggarans as $anggaran)
                                                                <div>{{ $anggaran ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        {{-- Jika data hanya satu, tampilkan seperti biasa --}}
                                                        <td class="align-middle">{{ $data->anggaran ?: '-' }}</td>
                                                    @endif

                                                    {{-- Cek untuk Kolom Sumber Dana --}}
                                                    @if (count($sumberdanas) > 1)
                                                        {{-- Jika data lebih dari satu, gunakan tampilan multi-baris --}}
                                                        <td class="multi-item-renja align-middle">
                                                            @foreach ($sumberdanas as $sumber)
                                                                <div>{{ $sumber ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        {{-- Jika data hanya satu, tampilkan seperti biasa --}}
                                                        <td class="align-middle">{{ $data->sumberdana ?: '-' }}</td>
                                                    @endif

                                                    <td class="text-center">
                                                        @if ($data->status === 'Valid')
                                                            <span class="badge bg-success">{{ $data->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $data->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $data->keterangan }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-1">
                                                            @if (auth()->guard('pengguna')->user()->level == 'Super Admin')
                                                                <button
                                                                    class="btn btn-sm {{ $data->status == 'Valid' ? 'btn-warning' : 'btn-success' }}"
                                                                    onclick="updateStatus('{{ $data->id }}', '{{ $data->status }}')">
                                                                    @if ($data->status == 'Valid')
                                                                        Batalkan
                                                                    @else
                                                                        Validasi
                                                                    @endif
                                                                </button>

                                                                <form id="form-status-{{ $data->id }}"
                                                                    action="{{ route('rencana.validasi', $data->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="">
                                                                </form>
                                                            @endif

                                                            @if ($data->is_locked)
                                                                {{-- Jika terkunci, tombol dinonaktifkan --}}
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    onclick="showLockedAlert()">
                                                                    <i class="fas fa-lock"></i> Edit
                                                                </button>
                                                            @else
                                                                {{-- Jika tidak terkunci, tombol berfungsi normal --}}
                                                                <form action="{{ route('rencana.edit', $data->id) }}"
                                                                    method="GET" style="display:inline;">
                                                                    <button class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @push('scripts')
                                                                <script src="{{ asset('js/kunciMonev.js') }}"></script>
                                                            @endpush
                                                            <form id="formDelete-{{ $data->id }}"
                                                                action="{{ route('rencana.delete', $data->id) }}"
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
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="mt-3">
                                    {{ $rencana->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
