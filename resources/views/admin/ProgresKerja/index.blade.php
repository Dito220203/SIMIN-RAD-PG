@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Progres</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</a></li>
                    <li class="breadcrumb-item active">Progres Kerja</li>
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

                                <form method="GET" class="input-group w-auto mb-1">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Data"
                                        value="{{ request('search') }}">
                                    <button class="btn btn-tambah-utama" type="submit">Cari</button>
                                    @if (request('search'))
                                        <a href="{{ route('progres') }}" class="btn btn-secondary">Reset</a>
                                    @endif
                                </form>

                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="detail-table" id="TableProgres">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            {{-- <th class="text-center" style="width: 30px;">Sub Program</th> --}}
                                            {{-- <th style="width: 100px;">Rencana Aksi / Aktivitas</th>
                                             <th style="width: 100px;">Sub Kegiatan</th>
                                             <th style="width: 100px;">Kegiatan</th> --}}
                                            <th>Rencana Aksi / Aktivitas</th>
                                            <th class="text-center">Tahun</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($progres as $data)
                                            <tr id="row-{{ $data->id }}">
                                                <td>{{ $progres->firstItem() + $loop->index }}</td>
                                                <td>
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
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <!-- Tombol Detail -->
                                                        <button type="button" class="btn btn-tambah-utama btn-sm" title="Lihat"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailModal{{ $data->id }}">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>

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
                                                                action="{{ route('progres.updateStatus', $data->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="">
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- End Table -->

                                @foreach ($progres as $data)
                                    <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="detailModalLabel{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-super-xl modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
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
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-2"></i>Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- End Table with stripped rows -->

                            </div>
                            <div class="mt-3">
                                {{ $progres->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        {{-- <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-800">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>Lokasi Peta
                                    </h6>
                                    @if ($data->monev && $data->monev->map)
                                        <div id="detailMapProgres{{ $data->id }}"
                                            class="detail-map-container rounded shadow-sm"
                                            style="height: 350px; width: 100%; border: 2px solid #e9ecef; z-index: 0;"
                                            data-latitude="{{ $data->monev->map->latitude }}"
                                            data-longitude="{{ $data->monev->map->longitude }}">
                                        </div>
                                    @else
                                        <div class="alert alert-light text-center d-flex flex-column align-items-center justify-content-center"
                                            style="height: 350px;">
                                            <i class="bi bi-map text-muted" style="font-size: 3rem;"></i>
                                            <p class="mb-0 mt-3 text-muted">Lokasi belum ditandai</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
    </main>
@endsection
@push('scripts')
    {{-- Pastikan library Leaflet sudah dimuat di layout utama atau di sini --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> --}}

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
