@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tabel Pengguna</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
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

                                <a href="{{ route('pengguna.create') }}" class="btn btn-tambah-utama">
                                    + Tambah Pengguna
                                </a>
                                <div class="col-12 col-lg-auto">
                                    <form method="GET" class="input-group w-auto">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Data"
                                            value="{{ request('search') }}">
                                        <button class="btn btn-tambah-utama" type="submit">Cari</button>
                                        @if (request('search'))
                                            <a href="{{ route('pengguna') }}" class="btn btn-secondary">Reset</a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="detail-table" id="TablePengguna">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Perangkat Daerah</th>
                                            <th>Level</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengguna as $data)
                                            <tr>
                                                <td>{{ $pengguna->firstItem() + $loop->index }}</td>
                                                <td>{{ $data->username }}</td>
                                                <td>{{ $data->opd->nama ?? '-' }}</td>



                                                <td>{{ $data->level }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <form action="{{ route('pengguna.edit', $data->id) }}"
                                                            method="GET">
                                                            <button class="btn btn-tambah-utama btn-sm">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                        </form>
                                                        <form id="formDelete-{{ $data->id }}"
                                                            action="{{ route('pengguna.destroy', $data->id) }}"
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
                            <div class="mt-3">
                                {{ $pengguna->links('vendor.pagination.bootstrap-5') }}
                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
