@extends('components.layout')
@section('content')

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Monitoring Evaluasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Monitoring Evaluasi</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="row g-3 align-items-center mb-3 mt-3">

                                @if (Auth::guard('pengguna')->user()->level === 'Super Admin')
                                    <div class="col-12 col-md-auto">
                                        <a href="{{ route('monev.export.excel', request()->query()) }}"
                                            class="btn btn-success w-100">
                                            <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                                        </a>
                                    </div>
                                @endif
                                <div class="col-12 col-md-auto">
                                    <a href="{{ route('monev.export', ['tahun' => request('tahun'), 'search' => request('search')]) }}"
                                        class="btn btn-danger w-100">
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </a>
                                </div>

                                {{-- Form Filter --}}
                                <div class="col-12 col-md-auto ms-md-auto">
                                    <form id="filter-form" method="GET" class="d-flex flex-column flex-md-row gap-2">
                                        <div class="input-group w-auto">
                                            <label class="input-group-text" for="tahun-filter"><i
                                                    class="fas fa-calendar-alt"></i></label>
                                            <select name="tahun" id="tahun-filter" class="form-select">
                                                <option value="">Semua Tahun</option>
                                                @foreach ($tahuns as $tahun)
                                                    <option value="{{ $tahun }}"
                                                        {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group w-auto">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Cari data..." value="{{ request('search') }}">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fas fa-search"></i></button>
                                            @if (request('search') || request('tahun'))
                                                <a href="{{ route('monev') }}" class="btn btn-secondary"
                                                    title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- --- aksi buka kunci --- --}}
                            @if (Auth::guard('pengguna')->user()->level === 'Super Admin' && isset($allOpds) && $allOpds->isNotEmpty())
                                <div class="card-body border-top pt-3">
                                    <h5 class="card-title" style="padding: 0 !important; margin-bottom: 5px;">Aksi Kunci
                                        Data per OPD</h5>
                                    <form action="{{ route('monev.bulk-lock') }}" method="POST" id="bulk-lock-form">
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
                            <div class="table-container">
                                <div class="top-scrollbar-container">
                                    <div class="top-scrollbar-content"></div>
                                </div>


                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="detail-table" id="TableMonev" style="min-width: 3000px;">
                                        @php
                                            $adaPesan = $monev->contains(function ($item) {
                                                return !empty($item->pesan);
                                            });
                                        @endphp
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th class="text-center">Strategi</th>
                                                <th class="text-center" style="width: 300px;">Rencana Aksi / Aktivitas</th>
                                                <th class="text-center" style="width: 200px;">Sub Kegiatan</th>
                                                <th class="text-center" style="width: 200px;">Kegiatan</th>
                                                <th class="text-center" style="width: 200px;">Program</th>
                                                <th class="text-center">Lokasi</th>
                                                <th class="text-center">Volume Target</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-center">Tahun</th>
                                                <th class="text-center">Perangkat Daerah</th>
                                                <th class="text-center" style="width: 300px;">Anggaran</th>
                                                <th class="text-center" style="width: 200px;">Sumber Dana</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Dokumen Anggaran</th>
                                                <th class="text-center">Realisasi Anggaran</th>
                                                <th class="text-center">Volume Realisasi</th>
                                                <th class="text-center">Satuan Volume</th>
                                                <th class="text-center" style="width: 400px;">Keterangan</th>
                                                @if ($adaPesan)
                                                    <th class="text-center">Catatan</th>
                                                @endif
                                                <th class="text-center" style="width: 190px;">Dokumentasi</th>
                                                <th class="text-center" style="width: 400px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($monev as $data)
                                                <tr id="row-{{ $data->id }}">
                                                    <td class="text-center">{{ $monev->firstItem() + $loop->index }}</td>
                                                    <td class="text-center">{{ $data->subprogram->subprogram ?? '-' }}
                                                    </td>
                                                    <td>{{ $data->rencanakerja->rencana_aksi ?? '-' }}</td>
                                                    <td>{{ $data->rencanakerja->sub_kegiatan ?? '-' }}</td>
                                                    <td>{{ $data->rencanakerja->kegiatan ?? '-' }}</td>
                                                    <td>{{ $data->rencanakerja->nama_program ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->rencanakerja->lokasi ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->rencanakerja->volume ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->rencanakerja->satuan ?? '-' }}</td>
                                                    <td class="text-center">{{ $data->rencanakerja->tahun ?? '-' }}</td>
                                                    <td class="text-center" class="text-center">
                                                        {{ $data->opd->nama ?? '-' }}
                                                    </td>
                                                    @php
                                                        $anggarans = explode('; ', $data->anggaran);
                                                        $sumberdanas = explode('; ', $data->sumberdana);
                                                    @endphp

                                                    {{-- Cek untuk Kolom Anggaran --}}
                                                    @if (count($anggarans) > 1)
                                                        {{-- Jika data lebih dari satu, gunakan tampilan multi-baris --}}
                                                        <td class="multi-item text-center align-middle">
                                                            @foreach ($anggarans as $anggaran)
                                                                <div>{{ $anggaran ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        {{-- Jika data hanya satu, tampilkan seperti biasa --}}
                                                        <td class="text-center">{{ $data->anggaran ?: '-' }}</td>
                                                    @endif

                                                    {{-- Cek untuk Kolom Sumber Dana --}}
                                                    @if (count($sumberdanas) > 1)
                                                        {{-- Jika data lebih dari satu, gunakan tampilan multi-baris --}}
                                                        <td class="multi-item text-center align-middle">
                                                            @foreach ($sumberdanas as $sumber)
                                                                <div>{{ $sumber ?: '-' }}</div>
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        {{-- Jika data hanya satu, tampilkan seperti biasa --}}
                                                        <td class="text-center">{{ $data->sumberdana ?: '-' }}</td>
                                                    @endif


                                                    {{-- kolom status --}}
                                                    <td class="text-center">
                                                        @if ($data->status === 'Valid')
                                                            <span class="badge bg-success">{{ $data->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $data->status }}</span>
                                                        @endif
                                                    </td>

                                                    @php
                                                        // Definisikan array pemetaan angka ke Romawi di sini
                                                        // Indeks 0 sengaja dikosongkan agar $romanMap[1] menjadi 'I'
                                                        $romanMap = ['', 'I', 'II', 'III', 'IV'];
                                                    @endphp

                                                    {{-- Kolom Dokumen Anggaran (Tidak perlu diubah, sudah benar) --}}
                                                    <td class="text-center">
                                                        @forelse (($data->dokumen_anggaran ?? []) as $status)
                                                            @if ($status && str_contains($status, 'ADA'))
                                                                <span
                                                                    class="badge bg-success d-block mb-1">{{ $status }}</span>
                                                            @elseif ($status)
                                                                <span
                                                                    class="badge bg-danger d-block mb-1">{{ $status }}</span>
                                                            @endif
                                                        @empty
                                                            <span>-</span>
                                                        @endforelse
                                                    </td>

                                                    {{-- Kolom Realisasi (Diperbaiki dengan Flexbox) --}}
                                                    <td>
                                                        @if (is_array($data->realisasi))
                                                            @foreach ($data->realisasi as $triwulan => $nilai)
                                                                @if ($nilai)
                                                                    {{-- Bungkus setiap baris dengan div dan gunakan flex --}}
                                                                    <div style="display: flex; align-items: baseline;">
                                                                        {{-- Atur lebar tetap untuk label --}}
                                                                        <span style="width: 55px; display: inline-block;">
                                                                            TW {{ $romanMap[$triwulan] ?? $triwulan }}
                                                                        </span>
                                                                        <span>:</span>
                                                                        {{-- Beri sedikit jarak kiri --}}
                                                                        <span
                                                                            style="margin-left: 5px;">{{ $nilai }}</span>

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $data->realisasi }}
                                                        @endif
                                                    </td>

                                                    {{-- Kolom Volume realisasi (Diperbaiki dengan Flexbox) --}}
                                                    <td>
                                                        @if (is_array($data->volumeTarget))
                                                            @foreach ($data->volumeTarget as $triwulan => $nilai)
                                                                @if ($nilai)
                                                                    <div style="display: flex; align-items: baseline;">
                                                                        <span style="width: 55px; display: inline-block;">
                                                                            TW {{ $romanMap[$triwulan] ?? $triwulan }}
                                                                        </span>
                                                                        <span>:</span>
                                                                        <span
                                                                            style="margin-left: 5px;">{{ $nilai }}</span>

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $data->volumeTarget }}
                                                        @endif
                                                    </td>

                                                    {{-- Kolom Satuan Realisasi (Diperbaiki dengan Flexbox) --}}
                                                    <td>
                                                        @if (is_array($data->satuan_realisasi))
                                                            @foreach ($data->satuan_realisasi as $triwulan => $nilai)
                                                                @if ($nilai)
                                                                    <div style="display: flex; align-items: baseline;">
                                                                        <span style="width: 55px; display: inline-block;">
                                                                            TW {{ $romanMap[$triwulan] ?? $triwulan }}
                                                                        </span>
                                                                        <span>:</span>
                                                                        <span
                                                                            style="margin-left: 5px;">{{ $nilai }}</span>

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $data->satuan_realisasi }}
                                                        @endif
                                                    </td>
                                                    {{-- kolom uraian --}}
                                                    <td>
                                                        @if (is_array($data->uraian))
                                                            @foreach ($data->uraian as $triwulan => $nilai)
                                                                @if ($nilai)
                                                                    <div style="display: flex; align-items: baseline;">
                                                                        <span style="width: 55px; display: inline-block;">
                                                                            TW {{ $romanMap[$triwulan] ?? $triwulan }}
                                                                        </span>
                                                                        <span>:</span>
                                                                        <span
                                                                            style="margin-left: 5px;">{{ $nilai }}</span>

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{ $data->uraian }}
                                                        @endif
                                                    </td>
                                                    @if ($adaPesan)
                                                        <td>{{ $data->pesan }}</td>
                                                    @endif

                                                    {{-- Tombol Lihat Dokumentasi --}}
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-tambah-utama btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ModalDetailProduk{{ $data->id }}">
                                                            Lihat Dokumentasi
                                                        </button>
                                                    </td>






                                                    <td class="text-center align-middle">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#uploadFotoModal"
                                                                data-id="{{ $data->id }}">
                                                                <i class="fas fa-camera"></i> Upload
                                                            </button>

                                                            @if ($data->is_locked)
                                                                {{-- Jika terkunci, tombol dinonaktifkan --}}
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    onclick="showLockedAlert()">
                                                                    <i class="fas fa-lock"></i> Edit/Lengkapi
                                                                </button>
                                                            @else
                                                                {{-- Jika tidak terkunci, tombol berfungsi normal --}}
                                                                <form action="{{ route('monev.edit', $data->id) }}"
                                                                    method="GET" style="display:inline;">
                                                                    <button class="btn btn-tambah-utama btn-sm">
                                                                        <i class="fas fa-edit"></i> Edit/Lengkapi
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @push('scripts')
                                                                <script src="{{ asset('js/kunciMonev.js') }}"></script>
                                                            @endpush

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
                                                                    action="{{ route('monev.validasi', $data->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="">
                                                                </form>
                                                                <button type="button" class="btn btn-tambah-utama btn-sm"
                                                                    data-bs-toggle="modal" data-bs-target="#modalPesan"
                                                                    data-id="{{ $data->id }}"
                                                                    data-pesan="{{ $data->pesan ?? '' }}">
                                                                    <i class="fa-solid fa-envelope"></i>
                                                                </button>
                                                            @endif



                                                            {{-- Tombol Delete --}}
                                                            <form id="formDelete-{{ $data->id }}"
                                                                action="{{ route('monev.delete', $data->id) }}"
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



                                    <!-- Modal Pesan (satu saja, di luar foreach) -->
                                    <div class="modal fade" id="modalPesan" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form id="formPesan" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="id_monev" id="idMonev">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Kirim Catatan ke Admin Perangkat Daerah
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan</label>
                                                            <textarea name="pesan" id="inputPesan" class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>



                                    {{-- modal detail --}}
                                    @foreach ($monev as $data)
                                        <div class="modal fade" id="ModalDetailProduk{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="DetailLabel{{ $data->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-super-xl modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="DetailLabel{{ $data->id }}">
                                                            <i class="bi bi-info-circle me-2"></i>Detail Monitoring &
                                                            Evaluasi
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-4">

                                                            <div class="col-md-6">
                                                                <div class="keterangan-panjang">
                                                                    <h6 class="mb-3 fw-bold"><i
                                                                            class="bi bi-list-ul text-primary me-2"></i>Detail
                                                                        Dokumentasi</h6>
                                                                    <div>
                                                                        <h6 class="mb-2 fw-bold"><i
                                                                                class="bi bi-card-text me-2"></i>Keterangan
                                                                        </h6>

                                                                        <div class="keterangan-box">
                                                                            <p>
                                                                                @if ($data->fotoProgres->isNotEmpty())
                                                                                    {{ $data->fotoProgres->first()->deskripsi ?: 'Tidak ada uraian.' }}
                                                                                @else
                                                                                    <span
                                                                                        class="text-muted fst-italic">Tidak
                                                                                        ada uraian</span>
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="mt-4">
                                                                    <h6 class="mb-3 fw-bold"><i
                                                                            class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi
                                                                        Peta</h6>
                                                                    @if ($data->map && $data->map->latitude && $data->map->longitude)
                                                                        <div id="detailMap{{ $data->id }}"
                                                                            class="peta-container rounded shadow-sm"
                                                                            data-latitude="{{ $data->map->latitude }}"
                                                                            data-longitude="{{ $data->map->longitude }}">
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="alert alert-light placeholder-container">
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
                                                                    @if ($data->fotoProgres->isNotEmpty())
                                                                        <div class="row g-3">
                                                                            @foreach ($data->fotoProgres as $foto)
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
                                                                            <p class="mb-0 mt-2 text-muted">Belum ada foto
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-2"></i>Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $monev->links('vendor.pagination.bootstrap-5') }}
                                </div>

                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
                {{-- modal aplud --}}
                <div class="modal fade" id="uploadFotoModal" tabindex="-1" aria-labelledby="uploadFotoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form id="uploadFotoForm" method="POST" action="{{ route('foto-progres.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="monev_id" id="monev_id_input">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadFotoModalLabel">Upload Foto Dokumentasi & Lokasi
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="dropZone" class="drop-zone mb-3">
                                        <div class="drop-zone-content">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                            <p class="mb-1"><strong>Upload gambar progres</strong></p>
                                            <p class="text-muted small">Drag & drop atau klik (JPG, PNG, Maks 2MB)</p>
                                        </div>
                                        <input type="file" id="fileInput" name="foto[]" accept="image/*" multiple
                                            style="display: none;" required>
                                    </div>

                                    <div id="previewContainer" class="mb-3">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="deskripsi_input" class="form-label">Uraian</label>
                                        <textarea name="deskripsi" id="deskripsi_input" class="form-control" placeholder="Masukkan keterangan..."
                                            rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tandai Lokasi di Peta</label>
                                        <div id="map"
                                            style="height: 300px; width: 100%; border-radius: 8px; z-index: 0;">
                                        </div>
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


        </section>
    </main>

    @push('scripts')
        {{-- Script untuk auto-submit filter tahun --}}
        <script>
            $(document).ready(function() {
                $('#tahun-filter').on('change', function() {
                    $('#filter-form').submit();
                });
            });
        </script>

        {{-- Script untuk fungsionalitas MODAL UPLOAD --}}
        <script>
            $(document).ready(function() {
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('fileInput');
                const previewContainer = document.getElementById('previewContainer');
                const deskripsiInput = document.getElementById('deskripsi_input');
                let filesArray = [];

                let map;
                let marker;
                const defaultLat = -8.1689; // Ganti dengan koordinat default Anda
                const defaultLng = 113.223;
                const latInput = $('#latitude');
                const lngInput = $('#longitude');

                $('#uploadFotoModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var monevId = button.data('id');
                    $(this).find('#monev_id_input').val(monevId);
                });

                $('#uploadFotoModal').on('shown.bs.modal', function() {
                    if (!map) {
                        map = L.map('map').setView([defaultLat, defaultLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(map);
                        marker = L.marker([defaultLat, defaultLng], {
                            draggable: true
                        }).addTo(map);
                        latInput.val(defaultLat);
                        lngInput.val(defaultLng);

                        const searchControl = new GeoSearch.GeoSearchControl({
                            provider: new GeoSearch.OpenStreetMapProvider(),
                            style: 'bar',
                            autoClose: true,
                            keepResult: true,
                            searchLabel: 'Cari lokasi...'
                        });
                        map.addControl(searchControl);

                        map.on('click', e => {
                            marker.setLatLng(e.latlng);
                            latInput.val(e.latlng.lat);
                            lngInput.val(e.latlng.lng);
                        });

                        marker.on('dragend', e => {
                            const pos = e.target.getLatLng();
                            latInput.val(pos.lat);
                            lngInput.val(pos.lng);
                        });

                        map.on('geosearch/showlocation', result => {
                            const pos = L.latLng(result.location.y, result.location.x);
                            marker.setLatLng(pos);
                            latInput.val(result.location.y);
                            lngInput.val(result.location.x);
                        });
                    }
                    setTimeout(() => map.invalidateSize(), 10);
                });

                $('#uploadFotoModal').on('hidden.bs.modal', function() {
                    filesArray = [];
                    previewContainer.innerHTML = '';
                    fileInput.value = '';
                    deskripsiInput.value = '';

                    if (marker) {
                        const defaultLatLng = L.latLng(defaultLat, defaultLng);
                        marker.setLatLng(defaultLatLng);
                        map.setView(defaultLatLng, 13);
                        latInput.val(defaultLat);
                        lngInput.val(defaultLng);
                    }
                });

                dropZone.addEventListener('click', () => fileInput.click());
                dropZone.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropZone.classList.add('drag-over');
                });
                dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    dropZone.classList.remove('drag-over');
                    handleFiles(Array.from(e.dataTransfer.files));
                });
                fileInput.addEventListener('change', e => handleFiles(Array.from(e.target.files)));

                function handleFiles(files) {
                    files.forEach(file => {
                        if (!file.type.startsWith('image/') || file.size > 2 * 1024 * 1024) {
                            alert('Hanya file gambar (JPG, PNG) maks 2MB.');
                            return;
                        }
                        filesArray.push({
                            file: file,
                            id: Date.now() + Math.random()
                        });
                    });
                    renderPreviews();
                }

                function renderPreviews() {
                    previewContainer.innerHTML = '';
                    previewContainer.style.display = 'grid';
                    previewContainer.style.gridTemplateColumns = 'repeat(auto-fill, minmax(100px, 1fr))';
                    previewContainer.style.gap = '10px';
                    filesArray.forEach(item => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            previewContainer.insertAdjacentHTML('beforeend',
                                `<div style="position: relative;"><img src="${e.target.result}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;"><button type="button" class="btn btn-danger btn-sm" onclick="removeFile(${item.id})" style="position: absolute; top: 5px; right: 5px; line-height: 1; padding: 2px 5px; border-radius: 50%;">&times;</button></div>`
                            );
                        };
                        reader.readAsDataURL(item.file);
                    });
                    updateFileInput();
                }

                function updateFileInput() {
                    const dt = new DataTransfer();
                    filesArray.forEach(item => dt.items.add(item.file));
                    fileInput.files = dt.files;
                }
                window.removeFile = id => {
                    filesArray = filesArray.filter(item => item.id != id);
                    renderPreviews();
                };
            });
        </script>

        {{-- Script untuk fungsionalitas MODAL DETAIL --}}
        <script>
            document.addEventListener('shown.bs.modal', function(event) {
                const modal = event.target;
                const mapContainer = modal.querySelector('.peta-container');
                if (!mapContainer || mapContainer._leaflet_id) return;

                const lat = mapContainer.dataset.latitude;
                const lng = mapContainer.dataset.longitude;
                const mapId = mapContainer.id;

                const detailMap = L.map(mapId, {
                    center: [lat, lng],
                    zoom: 15,
                    scrollWheelZoom: false,
                    dragging: false,
                    zoomControl: true
                });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(detailMap);
                L.marker([lat, lng]).addTo(detailMap);

                setTimeout(() => detailMap.invalidateSize(), 200);
            });
        </script>
        {{-- Script untuk fungsionalitas MODAL PESAN --}}

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modalPesan = document.getElementById('modalPesan');
                modalPesan.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var idMonev = button.getAttribute('data-id');
                    var pesan = button.getAttribute('data-pesan') || '';

                    // isi hidden input
                    modalPesan.querySelector('#idMonev').value = idMonev;

                    // isi textarea dengan pesan lama (kalau ada)
                    modalPesan.querySelector('#inputPesan').value = pesan;

                    // set action form ke route updatePesan
                    var form = modalPesan.querySelector('#formPesan');
                    form.action = "/monev/" + idMonev + "/pesan";
                });
            });
        </script>
    @endpush

@endsection
