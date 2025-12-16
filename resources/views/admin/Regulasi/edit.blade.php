@extends('components.layout')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Regulasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Beranda</li>
                    <li class="breadcrumb-item">Regulasi</li>
                    <li class="breadcrumb-item active">Edit Regulasi</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 mt-3">

                                <form action="{{ route('regulasi.update', $regulasi->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Judul Regulasi --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Judul Regulasi</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="e_judul" class="form-control"
                                                value="{{ old('judul', $regulasi->judul) }}" required>
                                        </div>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-10">
                                            <input type="date" name="e_tanggal" class="form-control"
                                                value="{{ old('tanggal', $regulasi->tanggal) }}" required>
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select name="e_status" class="form-select" required>
                                                <option value="Aktif"
                                                    {{ old('status', $regulasi->status) == 'Aktif' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="Non Aktif"
                                                    {{ old('status', $regulasi->status) == 'Non Aktif' ? 'selected' : '' }}>
                                                    Non Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- File Upload --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Upload File</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="e_file" id="e-file-input" class="form-control"
                                                accept=".pdf">
                                            <small class="text-muted">* Hanya file PDF, Maksimal 2 MB</small>

                                            @if ($regulasi->file)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/regulasi/' . $regulasi->file) }}"
                                                        target="_blank" class="btn btn-info btn-sm">
                                                        Lihat File Lama
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <script>
                                        document.getElementById("e-file-input").addEventListener("change", function(e) {
                                            const file = e.target.files[0];
                                            if (file && file.size > 2 * 1024 * 1024) {
                                                alert("Ukuran file maksimal 2 MB!");
                                                e.target.value = "";
                                            }
                                        });
                                    </script>


                                    {{-- Tombol --}}
                                    <div class="row mb-3">
                                        <div class="col-sm-10 offset-sm-2 d-flex gap-2">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <a href="{{ route('regulasi') }}" class="btn btn-warning">Kembali</a>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
