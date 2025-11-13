@extends('components.layout')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Rencana Kerja</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Rencana Kerja</li>
                    <li class="breadcrumb-item active">Edit Rencana Kerja</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-4">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('rencana.update', $rencana->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Bagian Atas Form --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Strategi</label>
                                        <select name="sub_program" class="form-select" required>
                                            <option value="">-- Pilih Strategi --</option>
                                            @foreach ($subprogram as $data)
                                                <option value="{{ $data->id }}"
                                                    {{ $data->id == $rencana->id_subprogram ? 'selected' : '' }}>
                                                    {{ $data->subprogram }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rencana Aksi / Aktivitas</label>
                                        <input type="text" name="rencanaAksi" class="form-control"
                                            value="{{ old('rencanaAksi', $rencana->rencana_aksi) }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Sub Kegiatan</label>
                                        <input type="text" name="sub_kegiatan" class="form-control"
                                            value="{{ old('sub_kegiatan', $rencana->sub_kegiatan) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kegiatan</label>
                                        <input type="text" name="kegiatan" class="form-control"
                                            value="{{ old('kegiatan', $rencana->kegiatan) }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Program</label>
                                        <input type="text" name="nama_program" class="form-control"
                                            value="{{ old('nama_program', $rencana->nama_program) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" name="lokasi" class="form-control"
                                            value="{{ old('lokasi', $rencana->lokasi) }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Volume Target</label>
                                        <input type="text" name="volume" class="form-control"
                                            value="{{ old('volume', $rencana->volume) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="satuan" class="form-control"
                                            value="{{ old('satuan', $rencana->satuan) }}" required>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label class="form-label">Tahun</label>
                                        <input type="text" name="tahun" class="form-control"
                                            value="{{ old('tahun', $rencana->tahun) }}" required min="2000"
                                            max="2100">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Perangkat Daerah</label>
                                        @php $user = Auth::guard('pengguna')->user(); @endphp
                                        @if ($user && $user->level == 'Admin')
                                            <input type="hidden" name="id_opd" value="{{ $user->id_opd }}">
                                            <input type="text" class="form-control bg-light"
                                                value="{{ $user->opd->nama ?? '-' }}" readonly>
                                        @else
                                            <select name="id_opd" class="form-select" required>
                                                <option value="">-- Pilih OPD --</option>
                                                @foreach ($opd as $data)
                                                    {{-- Asumsi variabel data yang diedit adalah $rencanaKerja --}}
                                                    <option value="{{ $data->id }}" @selected(old('id_opd', $rencana->id_opd) == $data->id)>
                                                        {{ $data->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                {{-- Fieldset untuk Detail Pendanaan --}}
                                <fieldset class="border p-3 rounded-3 mb-3">
                                    <legend class="float-none w-auto px-2 h6">Detail Pendanaan</legend>

                                    <button type="button" class="btn btn-tambah-utama mb-3" data-bs-toggle="modal"
                                        data-bs-target="#anggaranModal">
                                        <i class="bi bi-plus-circle"></i> Tambah Anggaran & Sumber Dana
                                    </button>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" scope="col">Anggaran</th>
                                                    <th class="text-center" scope="col">Sumber Dana</th>
                                                    <th class="text-center" scope="col" style="width: 10%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="anggaran-table-body">
                                                {{-- MEMUAT DATA LAMA DARI DATABASE --}}
                                                @if (is_array($rencana->anggaran) && is_array($rencana->sumberdana))
                                                    @foreach ($rencana->anggaran as $index => $anggaranValue)
                                                        @php
                                                            $uniqueId = 'row-initial-' . $index;
                                                            $sumberDanaValue = $rencana->sumberdana[$index] ?? '';
                                                        @endphp
                                                        <tr id="{{ $uniqueId }}">
                                                            <td class="text-center">{{ $anggaranValue }}</td>
                                                            <td class="text-center">{{ $sumberDanaValue }}</td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm hapus-anggaran-row"
                                                                    data-target="{{ $uniqueId }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="hidden-inputs-container" style="display: none;">
                                        {{-- MEMUAT DATA LAMA SEBAGAI HIDDEN INPUT --}}
                                        @if (is_array($rencana->anggaran) && is_array($rencana->sumberdana))
                                            @foreach ($rencana->anggaran as $index => $anggaranValue)
                                                @php
                                                    $uniqueId = 'row-initial-' . $index;
                                                    $sumberDanaValue = $rencana->sumberdana[$index] ?? '';
                                                @endphp
                                                <div id="hidden-{{ $uniqueId }}">
                                                    <input type="hidden" name="anggaran[]"
                                                        value="{{ $anggaranValue }}">
                                                    <input type="hidden" name="sumberdana[]"
                                                        value="{{ $sumberDanaValue }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="mt-2">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="3" required>{{ old('keterangan', $rencana->keterangan) }}</textarea>
                                    </div>
                                </fieldset>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('rencanakerja') }}" class="btn btn-warning">Batal</a>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modal untuk Input Anggaran (Sama seperti di halaman create) --}}
    <div class="modal fade" id="anggaranModal" tabindex="-1" aria-labelledby="anggaranModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="anggaranModalLabel">Tambah Anggaran Dan Sumber Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal-anggaran-form">
                        <div class="mb-3">
                            <label for="modal-anggaran" class="form-label">Anggaran</label>
                            <input type="text" id="modal-anggaran" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal-sumberdana" class="form-label">Sumber Dana</label>
                            <select id="modal-sumberdana" class="form-select" required>
                                <option value="">-- Pilih Sumber Dana --</option>
                                <option value="APBN">APBN</option>
                                <option value="DAK">DAK</option>
                                <option value="APBD Kab">APBD Kab</option>
                                <option value="APBD Prov">APBD Prov</option>
                                <option value="BK Prov">BK Prov</option>
                                <option value="DBHCHT">DBHCHT</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3" id="modal-sumberdana-lainnya-container" style="display: none;">
                            <label for="modal-sumberdana-lainnya" class="form-label">Sumber Dana Lainnya</label>
                            <input type="text" id="modal-sumberdana-lainnya" class="form-control"
                                placeholder="Masukkan sumber dana lainnya">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="tambah-ke-tabel">Tambah ke Tabel</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- Memanggil file JavaScript eksternal --}}
    <script src="{{ asset('js/anggaranDansumberDana.js') }}"></script>
@endpush
