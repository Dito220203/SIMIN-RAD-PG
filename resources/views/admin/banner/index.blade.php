@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Banner</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Banner</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">

                            {{-- Jika klik edit tampilkan update, kalau tidak create --}}
                            @if (isset($bannerEdit))
                                <h5 class="card-title">Update Banner</h5>
                                <form action="{{ route('banner.update', $bannerEdit->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" name="e_judul" class="form-control"
                                            value="{{ $bannerEdit->judul }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="e_status" class="form-select" required>
                                            <option value="Aktif" {{ $bannerEdit->status == 'Aktif' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="Nonaktif"
                                                {{ $bannerEdit->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                        </select>
                                    </div>

                                    {{-- PERUBAHAN UNTUK UPDATE KE GAMBAR --}}
                                    <div class="mb-3">
                                        <label class="form-label">Upload Gambar</label>
                                        <input type="file" name="e_file" class="form-control" accept="image/*"
                                            {{-- Menerima hanya gambar --}} onchange="previewFile(event)">
                                        <div class="mt-2">
                                            @php
                                                $ext = pathinfo($bannerEdit->file, PATHINFO_EXTENSION);
                                            @endphp
                                            {{-- Ganti kondisi dari video menjadi gambar --}}
                                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img id="file-preview" src="{{ asset('storage/' . $bannerEdit->file) }}"
                                                    style="max-height: 150px; border:1px solid #ccc; padding:5px; display:block;">
                                            @else
                                                {{-- Jika file lama bukan gambar, tampilkan elemen img tersembunyi untuk preview baru --}}
                                                <img id="file-preview"
                                                    style="max-height: 150px; border:1px solid #ccc; padding:5px; display:none;">
                                            @endif
                                        </div>
                                    </div>
                                    {{-- AKHIR PERUBAHAN UNTUK UPDATE KE GAMBAR --}}

                                    <button type="submit" class="btn btn-tambah-utama w-100">Update</button>
                                    <a href="{{ route('banner') }}" class="btn btn-secondary w-100 mt-2">Batal</a>
                                </form>
                            @else
                                <h5 class="card-title">Tambah Banner</h5>
                                <form action="{{ route('banner.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul</label>
                                        <input type="text" name="judul" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Nonaktif">Nonaktif</option>
                                        </select>
                                    </div>

                                    {{-- PERUBAHAN UNTUK CREATE KE GAMBAR --}}
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Upload Gambar</label>
                                        <input type="file" name="file" class="form-control" accept="image/*"
                                            {{-- Menerima hanya gambar --}} onchange="previewFile(event)">
                                        <div class="mt-2">
                                            {{-- Mengganti tag <video> menjadi <img> --}}
                                            <img id="file-preview"
                                                style="max-height: 150px; display:none; border:1px solid #ccc; padding:5px;">
                                        </div>
                                    </div>
                                    {{-- AKHIR PERUBAHAN UNTUK CREATE KE GAMBAR --}}

                                    <button type="submit" class="btn btn-success w-100">Simpan</button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Banner</h5>

                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">
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
                                <div class="col-12 col-md-4">
                                    <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                        id="liveSearchInput">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="dataTable" class="detail-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataTabelBody">
                                        @foreach ($banner as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->judul }}</td>
                                                <td>
                                                    @if ($data->status === 'Aktif')
                                                        <span class="badge bg-success">{{ $data->status }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $data->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn btn-tambah-utama" data-bs-toggle="modal"
                                                        data-bs-target="#fileModal{{ $data->id }}">
                                                        Lihat
                                                    </button>
                                                    <div class="modal fade" id="fileModal{{ $data->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body text-center">
                                                                    @php
                                                                        $ext = pathinfo(
                                                                            $data->file,
                                                                            PATHINFO_EXTENSION,
                                                                        );
                                                                    @endphp
                                                                    {{-- Logika untuk menampilkan Gambar di Modal --}}
                                                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                                                        <img src="{{ asset('storage/' . $data->file) }}"
                                                                            class="img-fluid rounded">
                                                                        {{-- Hapus bagian video karena hanya menerima gambar --}}
                                                                    @else
                                                                        {{-- Tampilkan pesan jika bukan gambar (meskipun diharapakan harusnya gambar) --}}
                                                                        <p>File tidak dapat ditampilkan (bukan gambar).</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('banner.edit', $data->id) }}"
                                                            class="btn btn-tambah-utama btn-sm">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <form id="formDelete-{{ $data->id }}"
                                                            action="{{ route('banner.delete', $data->id) }}"
                                                            method="POST">
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
                            {{-- Tambahkan di bawah div class="table-responsive" --}}
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                                <div id="paginationInfo"></div>
                                <div id="paginationControls"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>


@endsection


@push('scripts')
    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('file-preview');

            if (!file) return;

            // PERUBAHAN: Cek tipe file adalah gambar (image/*)
            if (file.type.startsWith('image/')) {
                const fileURL = URL.createObjectURL(file);
                // PERUBAHAN: Gunakan tag <img> dan atur properti src
                preview.src = fileURL;
                preview.style.display = 'block';

            } else {
                // PERUBAHAN: Pesan error untuk gambar
                alert("Hanya diperbolehkan upload gambar!");
                event.target.value = ""; // reset input
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
