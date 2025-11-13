@extends('components.layout')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit / Lengkapi Monitoring Evaluasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Monitoring Evaluasi</li>
                    <li class="breadcrumb-item active">Edit / Lengkapi Monitoring Evaluasi</li>
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

                            <form action="{{ route('monev.update', $monev->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Bagian form atas --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Strategi</label>
                                        <input type="hidden" name="id_subprogram" value="{{ $monev->id_subprogram }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $monev->subprogram->subprogram ?? 'Data tidak ditemukan' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rencana Aksi</label>
                                        <input type="hidden"  value="{{ $monev->rencana_aksi }}">
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $monev->rencanakerja->rencana_aksi ?? 'Data tidak ditemukan' }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Sub Kegiatan</label>
                                        <input type="text" id="sub_kegiatan"
                                            value="{{ old('sub_kegiatan', $monev->rencanakerja->sub_kegiatan) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kegiatan</label>
                                        <input type="text" id="kegiatan"
                                            value="{{ old('kegiatan', $monev->rencanakerja->kegiatan) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Program</label>
                                        <input type="text" id="nama_program"
                                            value="{{ old('nama_program', $monev->rencanakerja->nama_program) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tahun</label>
                                        <input type="text" id="tahun"
                                            value="{{ old('tahun', $monev->rencanakerja->tahun) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Volume Target</label>
                                        <input type="text" value="{{ old('volume', $monev->rencanakerja->volume) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" value="{{ old('satuan', $monev->rencanakerja->satuan) }}"
                                            class="form-control bg-light" readonly>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" value="{{ old('lokasi', $monev->rencanakerja->lokasi) }}"
                                            class="form-control bg-light" readonly>
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
                                                    <option value="{{ $data->id }}"
                                                        {{ old('id_opd', $monev->id_opd) == $data->id ? 'selected' : '' }}>
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
                                                @if (is_array($monev->anggaran) && is_array($monev->sumberdana))
                                                    @foreach ($monev->anggaran as $index => $anggaranValue)
                                                        @php
                                                            $uniqueId = 'row-initial-' . $index;
                                                            $sumberDanaValue = $monev->sumberdana[$index] ?? '';
                                                        @endphp
                                                        <tr id="{{ $uniqueId }}">
                                                            <td class="text-center">
                                                                {{ $anggaranValue }}</td>
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
                                        @if (is_array($monev->anggaran) && is_array($monev->sumberdana))
                                            @foreach ($monev->anggaran as $index => $anggaranValue)
                                                @php
                                                    $uniqueId = 'row-initial-' . $index;
                                                    $sumberDanaValue = $monev->sumberdana[$index] ?? '';
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
                                </fieldset>
                                @push('scripts')
                                    <script src="{{ asset('js/anggaranDansumberDana.js') }}"></script>
                                @endpush
                                <fieldset class="border rounded-3 p-3 mb-3">
                                    <legend class="float-none w-auto px-3 h6">Data Per Triwulan</legend>
                                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#triwulanModal">
                                        <i class="bi bi-plus-circle"></i> Tambah Data Triwulan
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Triwulan</th>
                                                    <th class="text-center">Dokumen Anggaran</th>
                                                    <th class="text-center">Realisasi Anggaran</th>
                                                    <th class="text-center">Volume Realisasi</th>
                                                    <th class="text-center">Satuan Volume</th>
                                                    <th class="text-center">Keterangan</th>
                                                    <th class="text-center" style="width: 10%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="triwulan-table-body">
                                                @php
                                                    $all_data = [
                                                        'dokumen_anggaran' => $monev->dokumen_anggaran ?? [],
                                                        'realisasi' => $monev->realisasi ?? [],
                                                        'volumeTarget' => $monev->volumeTarget ?? [],
                                                        'satuan_realisasi' => $monev->satuan_realisasi ?? [],
                                                        'uraian' => is_array($monev->uraian) ? $monev->uraian : [],
                                                    ];
                                                    $romanMap = ['I', 'II', 'III', 'IV'];
                                                @endphp

                                                @for ($i = 1; $i <= 4; $i++)
                                                    @if (
                                                        !empty($all_data['dokumen_anggaran'][$i]) ||
                                                            !empty($all_data['realisasi'][$i]) ||
                                                            !empty($all_data['volumeTarget'][$i]) ||
                                                            !empty($all_data['satuan_realisasi'][$i]) ||
                                                            !empty($all_data['uraian'][$i]))
                                                        @php
                                                            $uniqueId = 'tw-row-initial-' . $i;
                                                            $twRoman = $romanMap[$i - 1] ?? $i;
                                                            $dokumenValue = $all_data['dokumen_anggaran'][$i] ?? '-';
                                                            $realisasiValue = $all_data['realisasi'][$i] ?? '-';
                                                            $volumeValue = $all_data['volumeTarget'][$i] ?? '-';
                                                            $satuanValue = $all_data['satuan_realisasi'][$i] ?? '-';
                                                            $uraianValue = $all_data['uraian'][$i] ?? '-';
                                                        @endphp
                                                        <tr id="{{ $uniqueId }}" data-tw="{{ $i }}">
                                                            <td class="text-center"><strong>TW
                                                                    {{ $twRoman }}</strong></td>
                                                            <td class="text-center">
                                                                {{-- =============================================== --}}
                                                                {{-- PERUBAHAN PHP UNTUK TAMPILAN DATA LAMA          --}}
                                                                {{-- =============================================== --}}
                                                                @php
                                                                    $parts = explode(' | ', $dokumenValue);
                                                                @endphp
                                                                @if (count($parts) === 3)
                                                                    <strong>{{ $parts[1] }} =
                                                                        {{ $parts[2] }}</strong>
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $realisasiValue }}</td>
                                                            <td class="text-center">{{ $volumeValue }}</td>
                                                            <td class="text-center">{{ $satuanValue }}</td>
                                                            <td>{{ $uraianValue }}</td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm hapus-triwulan-row"
                                                                    data-target="hidden-{{ $uniqueId }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="hidden-triwulan-inputs" style="display: none;">
                                        @for ($i = 1; $i <= 4; $i++)
                                            @if (
                                                !empty($all_data['dokumen_anggaran'][$i]) ||
                                                    !empty($all_data['realisasi'][$i]) ||
                                                    !empty($all_data['volumeTarget'][$i]) ||
                                                    !empty($all_data['satuan_realisasi'][$i]) ||
                                                    !empty($all_data['uraian'][$i]))
                                                @php
                                                    $uniqueId = 'tw-row-initial-' . $i;
                                                @endphp
                                                <div id="hidden-{{ $uniqueId }}">
                                                    <input type="hidden" name="dokumen_anggaran[{{ $i }}]"
                                                        value="{{ $all_data['dokumen_anggaran'][$i] ?? '' }}">
                                                    <input type="hidden" name="realisasi[{{ $i }}]"
                                                        value="{{ $all_data['realisasi'][$i] ?? '' }}">
                                                    <input type="hidden" name="volumeTarget[{{ $i }}]"
                                                        value="{{ $all_data['volumeTarget'][$i] ?? '' }}">
                                                    <input type="hidden" name="satuan_realisasi[{{ $i }}]"
                                                        value="{{ $all_data['satuan_realisasi'][$i] ?? '' }}">
                                                    <input type="hidden" name="uraian[{{ $i }}]"
                                                        value="{{ $all_data['uraian'][$i] ?? '' }}">
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </fieldset>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('monev') }}" class="btn btn-warning">Batal</a>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Modal Anggaran dan Sumberdana --}}
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

    {{-- Modal untuk Data Triwulan --}}
    <div class="modal fade" id="triwulanModal" tabindex="-1" aria-labelledby="triwulanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="triwulanModalLabel">Tambah Data Realisasi Triwulan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal-triwulan-form">
                        <div class="mb-3">
                            <label for="modal-triwulan-select" class="form-label">Pilih Triwulan</label>
                            <select id="modal-triwulan-select" class="form-select" required>
                                <option value="">-- Pilih Triwulan --</option>
                                <option value="1">Triwulan I</option>
                                <option value="2">Triwulan II</option>
                                <option value="3">Triwulan III</option>
                                <option value="4">Triwulan IV</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumen Anggaran
                                <span id="dokumen-anggaran-label-suffix" class="badge bg-secondary ms-2">RKA</span>
                            </label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="modal_dokumen_anggaran"
                                        id="modal_dokumen_ada" value="ADA" checked>
                                    <label class="form-check-label" for="modal_dokumen_ada">ADA</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modal_dokumen_anggaran"
                                        id="modal_dokumen_tidak" value="TIDAK">
                                    <label class="form-check-label" for="modal_dokumen_tidak">TIDAK</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="modal-realisasi" class="form-label">Realisasi Anggaran</label>
                            <input type="text" id="modal-realisasi" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal-volume" class="form-label">Volume Realisasi</label>
                            <input type="text" id="modal-volume" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal-satuan" class="form-label">Satuan Volume</label>
                            <input type="text" id="modal-satuan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modal-keterangan" class="form-label">Keterangan</label>
                            <textarea id="modal-keterangan" class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="tambah-triwulan-ke-tabel">Tambah ke Tabel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Script untuk modal anggaran
            $('#modal-anggaran').on('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    this.value = 'Rp. ' + parseInt(value).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }
            });


            // Format otomatis untuk input Realisasi Anggaran di modal Triwulan
            $('#modal-realisasi').on('input', function() {
                let value = this.value.replace(/\D/g, ''); // hanya angka
                if (value) {
                    this.value = 'Rp. ' + parseInt(value).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }
            });


            // Script untuk modal data triwulan
            function updateTriwulanOptions() {
                $('#modal-triwulan-select option').prop('disabled', false);
                $('#triwulan-table-body tr').each(function() {
                    const tw_index = $(this).data('tw');
                    if (tw_index) {
                        $('#modal-triwulan-select option[value="' + tw_index + '"]').prop('disabled', true);
                    }
                });
                let firstAvailable = $('#modal-triwulan-select option:not(:disabled):first');
                $('#modal-triwulan-select').val(firstAvailable.val());
                $('#modal-triwulan-select').trigger('change');
            }

            updateTriwulanOptions();

            $('#modal-triwulan-select').on('change', function() {
                const selectedTw = $(this).val();
                const label = (selectedTw >= 3) ? 'PRKA' : 'RKA';
                $('#dokumen-anggaran-label-suffix').text(label);
            });

            $('#tambah-triwulan-ke-tabel').on('click', function() {
                const tw_index = $('#modal-triwulan-select').val();
                const tw_text = $('#modal-triwulan-select option:selected').text().trim();

                if (!tw_index) {
                    alert('Silakan pilih Triwulan terlebih dahulu.');
                    return;
                }

                const dokumen_status = $('input[name="modal_dokumen_anggaran"]:checked').val();
                const realisasi = $('#modal-realisasi').val();
                const volume = $('#modal-volume').val();
                const satuan = $('#modal-satuan').val();
                const keterangan = $('#modal-keterangan').val();

                const twRoman = ['I', 'II', 'III', 'IV'][tw_index - 1];
                const twLabel = tw_index <= 2 ? 'RKA' : 'PRKA';
                const dokumen_value = `TW ${twRoman} | ${twLabel} | ${dokumen_status}`;

                const uniqueId = 'tw-row-dynamic-' + new Date().getTime();

                // =============================================================
                // PERUBAHAN JAVASCRIPT UNTUK TAMPILAN DATA BARU
                // =============================================================
                const dokumenDisplayText = `<strong>${twLabel} = ${dokumen_status}</strong>`;

                const tableRow = `
                    <tr id="${uniqueId}" data-tw="${tw_index}">
                        <td class="text-center"><strong>${tw_text}</strong></td>
                        <td class="text-center">${dokumenDisplayText}</td>
                        <td class="text-center">${realisasi || '-'}</td>
                        <td class="text-center">${volume || '-'}</td>
                        <td class="text-center">${satuan || '-'}</td>
                        <td>${keterangan || '-'}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm hapus-triwulan-row" data-target="hidden-${uniqueId}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                const hiddenInputs = `
                    <div id="hidden-${uniqueId}">
                        <input type="hidden" name="dokumen_anggaran[${tw_index}]" value="${dokumen_value}">
                        <input type="hidden" name="realisasi[${tw_index}]" value="${realisasi}">
                        <input type="hidden" name="volumeTarget[${tw_index}]" value="${volume}">
                        <input type="hidden" name="satuan_realisasi[${tw_index}]" value="${satuan}">
                        <input type="hidden" name="uraian[${tw_index}]" value="${keterangan}">
                    </div>
                `;

                $('#triwulan-table-body').append(tableRow);
                $('#hidden-triwulan-inputs').append(hiddenInputs);

                $('#modal-triwulan-form')[0].reset();
                $('#triwulanModal').modal('hide');
                updateTriwulanOptions();
            });

            $('#triwulan-table-body').on('click', '.hapus-triwulan-row', function() {
                const targetId = $(this).data('target');
                $(this).closest('tr').remove();
                $('#' + targetId).remove();
                updateTriwulanOptions();
            });
        });
    </script>
@endsection
