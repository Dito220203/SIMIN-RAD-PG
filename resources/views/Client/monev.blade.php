@extends('componentsClient.layout')
@section('content')
    <div class="container-fluid py-4">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white">Dashboard</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Monitoring Evaluasi</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Monitoring Evaluasi</h6>
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
                        <h6>Daftar Monitoring Evaluasi</h6>
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
                                        <th>Anggaran</th>
                                        <th>SUMBERDANA</th>
                                        <th>STATUS</th>
                                        <th>DOKUMEN ANGGARAN</th>
                                        <th>REALISASI ANGGARAN</th>
                                        <th>VOLUME REALISASI</th>
                                        <th>SATUAN VOLUME</th>
                                        <th>KETERANGAN</th>
                                        <th class="text-center">DOKUMENTASI</th>

                                    </tr>
                                </thead>
                                <tbody id="monev-table-body">
                                    @foreach ($Monev as $data)
                                        <tr id="row-{{ $data->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
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
                                                                <span style="margin-left: 5px;">{{ $nilai }}</span>

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
                                                                <span style="margin-left: 5px;">{{ $nilai }}</span>

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
                                                                <span style="margin-left: 5px;">{{ $nilai }}</span>

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
                                                                <span style="margin-left: 5px;">{{ $nilai }}</span>

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $data->uraian }}
                                                @endif
                                            </td>


                                            {{-- Tombol Lihat Dokumentasi --}}
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#ModalDetailProduk{{ $data->id }}">
                                                    Lihat Dokumentasi
                                                </button>
                                            </td>

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
                        {{-- modal detail --}}
                        @foreach ($Monev as $data)
                            <div class="modal fade" id="ModalDetailProduk{{ $data->id }}" tabindex="-1"
                                aria-labelledby="DetailLabel{{ $data->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-super-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header  ">
                                            <h5 class="modal-title" id="DetailLabel{{ $data->id }}">
                                                <i class="bi bi-info-circle me-2"></i>Detail Monitoring &
                                                Evaluasi
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
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
                                                                        <span class="text-muted fst-italic">Tidak
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
                                                        @if ($data->fotoProgres->isNotEmpty())
                                                            <div class="row g-3">
                                                                @foreach ($data->fotoProgres as $foto)
                                                                    <div class="col-12">
                                                                        <a href="{{ asset('storage/' . $foto->foto) }}"
                                                                            target="_blank" class="d-block hover-effect">
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
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- Pastikan library Leaflet sudah dimuat di layout utama atau di sini --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> --}}
    <script src="{{ asset('js/progres-tabel.js') }}"></script>
    <script>
        // Event listener ini akan berjalan untuk SEMUA modal di halaman
        document.addEventListener('shown.bs.modal', function(event) {
            // Cari kontainer peta di dalam modal yang BARU SAJA DIBUKA
            const modal = event.target;
            const mapContainer = modal.querySelector('.peta-container');

            // Jika tidak ada kontainer peta di modal ini, atau peta sudah dibuat, hentikan
            if (!mapContainer || mapContainer._leaflet_id) {
                return;
            }

            const lat = mapContainer.dataset.latitude;
            const lng = mapContainer.dataset.longitude;
            const mapId = mapContainer.id;

            // Inisialisasi peta dalam mode 'view-only'
            const detailMap = L.map(mapId, {
                center: [lat, lng],
                zoom: 15,
                scrollWheelZoom: false, // Matikan zoom scroll
                dragging: false, // Matikan drag
                zoomControl: true // Tampilkan kontrol zoom +/-
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(detailMap);

            // Tambahkan penanda yang tidak bisa digeser
            L.marker([lat, lng]).addTo(detailMap);

            // Penting: Sesuaikan ukuran peta setelah modal tampil
            setTimeout(function() {
                detailMap.invalidateSize();
            }, 200);
        });
    </script>
@endpush
