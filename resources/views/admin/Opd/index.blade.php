@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Opd</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Opd</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card ">
                        <div class="card-body">
                            <!-- Header control: Tambah, Search, Tampilkan Data -->
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">

                                <!-- Tombol Trigger Modal -->
                                <button type="button" class="btn btn-tambah-utama" data-bs-toggle="modal"
                                    data-bs-target="#modalOpd">
                                    + Tambah Opd
                                </button>
                                <!-- modal -->
                                <div class="modal fade" id="modalOpd" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tambah Opd</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('opd.store') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label>Nama Opd</label>
                                                        <input type="text" class="form-control" name="nama" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Jenis</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="">Pilih</option>
                                                            <option value="Internal">Internal</option>
                                                            <option value="Eksternal">Eksternal</option>
                                                        </select>
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
                                </div>
                                <div class="col-12 col-lg-auto">
                                    <form method="GET" class="input-group w-auto">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Data"
                                            value="{{ request('search') }}">
                                        <button class="btn btn-tambah-utama" type="submit">Cari</button>
                                        @if (request('search'))
                                            <a href="{{ route('opd') }}" class="btn btn-secondary">Reset</a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="detail-table" id="TableOpd">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($opd as $data)
                                            <tr>
                                                <td>{{ $opd->firstItem() + $loop->index }}</td>
                                                <td>{{ $data->nama }}</td>
                                                <td> {{ $data->status }}</td>
                                                <td>
                                                    <div>
                                                        <button type="button" class="btn btn-tambah-utama btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#Modalupdateopd{{ $data->id }}">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        </form>
                                                        <form id="formDelete-{{ $data->id }}"
                                                            action="{{ route('opd.destroy', $data->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete('{{ $data->id }}')">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                                <!-- Modal Update -->
                                                <div class="modal fade" id="Modalupdateopd{{ $data->id }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update Gambaran Umum</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('opd.update', $data->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <div class="mb-3">
                                                                        <label>Nama</label>
                                                                        <input type="text" class="form-control"
                                                                            name="e_nama" value="{{ $data->nama }}"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Status</label>
                                                                        <select name="e_status" class="form-select"
                                                                            required>
                                                                            <option value="Internal"
                                                                                {{ $data->status == 'Internal' ? 'selected' : '' }}>
                                                                                Internal</option>
                                                                            <option value="Eksternal"
                                                                                {{ $data->status == 'Eksternal' ? 'selected' : '' }}>
                                                                                Eksternal</option>
                                                                        </select>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit"
                                                                            class="btn btn-tambah-utama">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $opd->links('vendor.pagination.bootstrap-5') }}
                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        // 1. Menampilkan alert untuk pesan SUKSES (dari store, update, & destroy)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        // 2. Menampilkan alert untuk SEMUA jenis pesan GAGAL VALIDASI
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                // Mengambil pesan error pertama yang ada, tidak peduli dari field 'nama' atau 'e_nama'
                text: "{{ $errors->first() }}",
            });

            // Logika untuk membuka kembali modal 'Tambah Data' jika error terjadi di sana
            @if ($errors->has('nama'))
                var myModal = new bootstrap.Modal(document.getElementById('modalOpd'));
                myModal.show();
            @endif
        @endif

        // 3. Fungsi konfirmasi hapus data (tidak berubah)
        function confirmDelete(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete-' + id).submit();
                }
            });
        }
    </script>
@endpush
