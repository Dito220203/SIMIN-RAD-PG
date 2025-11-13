    @extends('components.layout')
    @section('content')
        <main id="main" class="main">
            <div class="pagetitle">
                <h1>Tabel Strategi</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Beranda</li>
                        <li class="breadcrumb-item active">Strategi</li>
                    </ol>
                </nav>
            </div>

            <section class="section">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3 mt-3">
                                        <button type="button" class="btn btn-tambah-utama " data-bs-toggle="modal"
                                            data-bs-target="#modalSubProgram">
                                            Tambah Strategi
                                        </button>


                                        <!-- Modal Tambah Sub Program -->
                                        <div class="modal fade" id="modalSubProgram" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tambah Strategi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('subrogram.store') }}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label>Program</label>
                                                                <select name="program" class="form-select" required>
                                                                    <option value="">Pilih</option>
                                                                    <option value="Program 1">Program 1</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Judul Strategi</label>
                                                                <input type="text" class="form-control" name="subprogram"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Uraian</label>
                                                                <textarea class="form-control" name="uraian" rows="4" required></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- Pencarian -->
                                        <form method="GET" class="input-group w-auto mb-2">
                                            <input type="text" name="search_sub" class="form-control"
                                                placeholder="Cari Strategi" value="{{ request('search_sub') }}">
                                            <button class="btn btn-tambah-utama" type="submit">Cari</button>
                                            @if (request('search_sub'))
                                                <a href="{{ route('subprogram') }}" class="btn btn-secondary">Reset</a>
                                            @endif
                                        </form>

                                    </div>
                                </div>
                                <!-- Table Sub Program -->
                                <div class="table-responsive">
                                    <table class="detail-table" id="TableSubprogram">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Program</th>
                                                <th class="text-center">Strategi</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subprogram as $data)
                                                <tr>
                                                    <td class="text-center">{{ $subprogram->firstItem() + $loop->index }}
                                                    </td>
                                                    <td class="text-center">{{ $data->program }}</td>
                                                    <td class="text-center">{{ $data->subprogram }}</td>
                                                    <td class="text-center align-middle">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <!-- Detail -->
                                                            <button type="button" class="btn btn-info btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ModalDetailSub{{ $data->id }}">
                                                                <i class="fa-solid fa-circle-info"></i>
                                                            </button>
                                                            <!-- Edit -->
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalupdate{{ $data->id }}">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <!-- Hapus -->
                                                            <form id="formDelete-{{ $data->id }}"
                                                                action="{{ route('subrogram.delete', $data->id) }}"
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

                                                    <!-- Modal Update Sub Program -->
                                                    <div class="modal fade" id="Modalupdate{{ $data->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit Sub Program</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form
                                                                        action="{{ route('subprogram.update', $data->id) }}"
                                                                        method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="mb-3">
                                                                            <label>Program</label>
                                                                            <select name="e_program" class="form-select"
                                                                                required>
                                                                                <option value="">Pilih</option>
                                                                                <option value="Program 1"
                                                                                    {{ $data->program == 'Program 1' ? 'selected' : '' }}>
                                                                                    Program 1</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Judul Sub Program</label>
                                                                            <input type="text" class="form-control"
                                                                                name="e_subprogram"
                                                                                value="{{ $data->subprogram }}" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label>Uraian</label>
                                                                            <textarea class="form-control" name="e_uraian" rows="4" required>{{ $data->uraian }}</textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Update</button>
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
                                    {{ $subprogram->links('vendor.pagination.bootstrap-5') }}
                                </div>
                                <!-- End Table Sub Program -->
                            </div>
                        </div>
                    </div>
                    @foreach ($subprogram as $data)
                        <!-- Modal Detail Sub Program -->
                        <div class="modal fade" id="ModalDetailSub{{ $data->id }}" tabindex="-1"
                            aria-labelledby="DetailSubLabel{{ $data->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="DetailSubLabel{{ $data->id }}">Detail Sub Program
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Program:</strong> {{ $data->program }}</p>
                                        <p><strong>Sub Program:</strong> {{ $data->subprogram }}</p>
                                        <p><strong>Uraian:</strong></p>
                                        <div class="border p-2 rounded bg-light">
                                            {!! nl2br(e($data->uraian)) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach



            </section>
        </main>
    @endsection
    @push('scripts')
        <script>
            // Preview foto create
            document.getElementById('fotoInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    e.target.value = '';
                    document.getElementById('previewFoto').style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('previewFoto');
                    img.src = event.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            });
        </script>
    @endpush
