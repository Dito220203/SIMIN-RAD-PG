@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Regulasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item active">Regulasi</li>
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

                                <a href="{{ route('regulasi.create') }}" class="btn btn-tambah-utama">
                                    + Tambah regulasi
                                </a>
                                {{-- <div class="col-12 col-lg-auto"> --}}
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
                                   <div class="input-group w-auto">
                                        <input type="text" class="form-control" placeholder="Cari di halaman ini..."
                                            id="liveSearchInput">

                                    </div>
                                {{-- </div> --}}
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="detail-table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th data-type="date" data-format="YYYY/DD/MM">Tanggal Dibuat</th>
                                            <th>Status</th>
                                            <th>File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataTabelBody">
                                        @foreach ($regulasi as $data)
                                            <tr>
                                                <td>{{ $loop->index }}</td>
                                                <td>{{ $data->judul }}</td>
                                                <td>{{ $data->tanggal }}</td>
                                                <td>
                                                    @if ($data->status === 'Aktif')
                                                        <span class="badge bg-success">{{ $data->status }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $data->status }}</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#fileModal{{ $data->id }}">
                                                        Lihat
                                                    </button>


                                                    <!-- Modal -->
                                                    <div class="modal fade" id="fileModal{{ $data->id }}" tabindex="-1"
                                                        aria-labelledby="fileModalLabel{{ $data->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="fileModalLabel{{ $data->id }}">Lihat File
                                                                        Regulasi</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <iframe
                                                                        src="{{ asset('storage/regulasi/' . $data->file) }}"
                                                                        width="100%" height="600px"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <form action="{{ route('regulasi.edit', $data->id) }}"
                                                            method="GET">
                                                            <button class="btn btn-tambah-utama btn-sm">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                        </form>
                                                        <form id="formDelete-{{ $data->id }}"
                                                            action="{{ route('regulasi.delete', $data->id) }}"
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
                             <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                                    <div id="paginationInfo"></div>
                                    <div id="paginationControls"></div>
                                </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
    </main>
@endsection
