 @extends('components.layout')
 @section('content')
     <main id="main" class="main">

         <div class="pagetitle">
             <h1>Tambah Regulasi</h1>
             <nav>
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item">Beranda</a></li>
                     <li class="breadcrumb-item">Regulasi</li>
                     <li class="breadcrumb-item active">Tambah Regulasi</li>
                 </ol>
             </nav>
         </div><!-- End Page Title -->

         <section class="section">
             <div class="row">
                 <div class="col-lg-12"> {{-- Ubah jadi full width jika butuh --}}
                     <div class="card">
                         <div class="card-body">
                             <div class="mb-3 mt-3">

                                 <form action="{{ route('regulasi.store') }}" method="POST" enctype="multipart/form-data">
                                     @csrf

                                     {{-- Judul Informasi --}}
                                     <div class="row mb-3">
                                         <label class="col-sm-2 col-form-label">Judul Regulasi</label>
                                         <div class="col-sm-10">
                                             <input type="text" name="judul" class="form-control" required>
                                         </div>
                                     </div>
                                     <div class="row mb-3">
                                         <label class="col-sm-2 col-form-label">Tanggal</label>
                                         <div class="col-sm-10">
                                             <input type="date" name="tanggal" class="form-control" required>
                                         </div>
                                     </div>

                                     {{-- Status Informasi --}}
                                     <div class="row mb-3">
                                         <label class="col-sm-2 col-form-label">Status</label>
                                         <div class="col-sm-10">
                                             <select name="status" class="form-select" required>
                                                 <option value="">Pilih</option>
                                                 <option value="Aktif">Aktif</option>
                                                 <option value="Non Aktif">Non Aktif</option>
                                             </select>
                                         </div>
                                     </div>

                                     {{-- File Upload --}}
                                     <div class="row mb-3">
                                         <label class="col-sm-2 col-form-label">Upload File</label>
                                         <div class="col-sm-10">
                                             <input type="file" name="file" id="file-input" class="form-control"
                                                 accept=".pdf" required>
                                             <small class="text-muted">* Hanya file PDF, Maksimal 2 MB</small>
                                         </div>
                                     </div>
                                     <script>
                                         document.getElementById("file-input").addEventListener("change", function(e) {
                                             const file = e.target.files[0];
                                             if (file && file.size > 2 * 1024 * 1024) {
                                                 alert("Ukuran file maksimal 2 MB!");
                                                 e.target.value = ""; // reset input
                                             }
                                         });
                                     </script>

                                     {{-- Tombol --}}
                                     <div class="row mb-3">
                                         <div class="col-sm-10 offset-sm-2 d-flex gap-2">
                                             <button type="submit" class="btn btn-success">Simpan</button>
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
