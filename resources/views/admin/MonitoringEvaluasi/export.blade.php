<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring dan Evaluasi</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .header-section {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #375534;
        }

        .header-section h1 {
            font-size: 16px;
            font-weight: bold;
            color: #0F2A1D;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .info-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .info-row {
            display: inline-block;
            margin-right: 25px;
            font-weight: bold;
        }

        .info-label {
            color: #0F2A1D;
        }

        .info-value {
            color: #6B9071;
            background-color: #E3EED4;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 3px;
        }

        .table-container {
            width: 100%;
            overflow: visible;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            background: white;
        }

        thead {
            background-color: #375534;
            color: white;
        }

        thead th {
            font-weight: bold;
            font-size: 7px;
            text-transform: uppercase;
            padding: 6px 3px;
            border: 1px solid #fff;
            text-align: center;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        td {
            padding: 4px 2px;
            border: 1px solid #dee2e6;
            font-size: 7px;
            word-wrap: break-word;
            vertical-align: top;
            line-height: 1.2;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 6px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .status-valid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #f8d7da;
            color: #721c24;
        }

        .rka-sudah {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .rka-belum {
            background-color: #fff3cd;
            color: #856404;
        }

        .currency {
            font-weight: bold;
            color: #6B9071;
            font-size: 6px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 8px;
        }

        .long-text {
            font-size: 6px;
            line-height: 1.1;
            word-break: break-word;
        }
    </style>
</head>

<body>
    <div class="header-section">
        <h1>Monitoring dan Evaluasi RAD-PG</h1>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Periode Tahun:</span>
            <span class="info-value">{{ $tahun ?? 'Semua Tahun' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span>
            <span class="info-value">{{ count($monev) }} Data</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ date('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 2%;">No</th>
                    <th style="width: 6%;">Strategi</th>
                    <th style="width: 8%;">Rencana Aksi</th>
                    <th style="width: 8%;">Sub Kegiatan</th>
                    <th style="width: 8%;">Kegiatan</th>
                    <th style="width: 8%;">Program</th>
                    <th style="width: 5%;">Lokasi</th>
                    <th style="width: 2%;">Vol Target</th>
                    <th style="width: 3%;">Satuan</th>
                    <th style="width: 4%;">Anggaran</th>
                    <th style="width: 4%;">Sumber Dana</th>
                    <th style="width: 3%;">Tahun</th>
                    <th style="width: 6%;">Perangkat Daerah</th>
                    <th style="width: 7%;">Dokumen Anggaran</th>
                    <th style="width: 7%;">Realisasi Anggaran</th>
                    <th style="width: 6%;">Vol Realisasi</th>
                    <th style="width: 6%;">Satuan Volume</th>
                    <th style="width: 4%;">Status</th>
                    <th style="width: 4%;">Catatan</th>
                    <th style="width: 4%;">Ket</th>
                </tr>
            </thead>
            {{-- GANTI SELURUH ISI <tbody> ANDA DENGAN KODE DI BAWAH INI --}}
            <tbody>
                @foreach ($monev as $i => $row)
                    <tr>
                        {{-- No --}}
                        <td class="text-center">{{ $i + 1 }}</td>

                        {{-- Kolom Data Utama --}}
                        <td class="text-left long-text">{{ $row->subprogram->subprogram ?? '-' }}</td>
                        <td class="text-left long-text">{{ $row->rencanakerja->rencana_aksi ?? '-' }}</td>
                        <td class="text-left long-text">{{ $row->rencanakerja->sub_kegiatan ?? '-'}}</td>
                        <td class="text-left long-text">{{ $row->rencanakerja->kegiatan ?? '-' }}</td>
                        <td class="text-left long-text">{{ $row->rencanakerja->nama_program ?? '-'}}</td>
                        <td class="text-center">{{ $row->rencanakerja->lokasi ?? '-'}}</td>
                        <td class="text-center">{{ $row->rencanakerja->volume ?? '-' }}</td>
                        <td class="text-center">{{ $row->rencanakerja->satuan ?? '-'}}</td>

                        {{-- ANGGARAN --}}
                        <td class="text-center" style="padding: 0;">
                            @php $anggarans = explode('; ', $row->anggaran); @endphp
                            @foreach ($anggarans as $anggaran)
                                <div
                                    style="padding: 4px 2px; @if (!$loop->last) border-bottom: 1px solid #dee2e6; @endif">
                                    {{ $anggaran ?: '-' }}
                                </div>
                            @endforeach
                        </td>

                        {{-- SUMBER DANA --}}
                        <td class="text-center" style="padding: 0;">
                            @php $sumberdanas = explode('; ', $row->sumberdana); @endphp
                            @foreach ($sumberdanas as $sumber)
                                <div
                                    style="padding: 4px 2px; @if (!$loop->last) border-bottom: 1px solid #dee2e6; @endif">
                                    {{ $sumber ?: '-' }}
                                </div>
                            @endforeach
                        </td>

                        {{-- Tahun & OPD --}}
                        <td class="text-center">{{ $row->rencanakerja->tahun ?? '-' }}</td>
                        <td class="text-center">{{ $row->opd->nama ?? '-' }}</td>

                        {{-- DOKUMEN ANGGARAN --}}
                        <td class="text-center">
                            @if (is_array($row->dokumen_anggaran) && !empty(array_filter($row->dokumen_anggaran)))
                                @foreach ($row->dokumen_anggaran as $status)
                                    @if ($status)
                                        <span
                                            class="status-badge {{ str_contains($status, 'ADA') ? 'rka-sudah' : 'rka-belum' }}">{{ $status }}</span><br>
                                    @endif
                                @endforeach
                            @else
                                -
                            @endif
                        </td>

                        {{-- REALISASI --}}
                        <td class="text-left">
                            @if (is_array($row->realisasi) && !empty(array_filter($row->realisasi)))
                                @php
                                    $romanMap = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
                                    $outputLines = [];
                                    foreach ($row->realisasi as $triwulan => $nilai) {
                                        if ($nilai) {
                                            $tw = $romanMap[$triwulan] ?? $triwulan;
                                            $outputLines[] = "TW {$tw}: <strong>{$nilai}</strong>";
                                        }
                                    }
                                    echo implode('<br>', $outputLines);
                                @endphp
                            @else
                                <span style="display: block; text-align: center;">-</span>
                            @endif
                        </td>

                        {{-- VOL REALISASI --}}
                        <td class="text-left">
                            @if (is_array($row->volumeTarget) && !empty(array_filter($row->volumeTarget)))
                                @php
                                    $romanMap = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
                                    $outputLines = [];
                                    foreach ($row->volumeTarget as $triwulan => $nilai) {
                                        if ($nilai) {
                                            $tw = $romanMap[$triwulan] ?? $triwulan;
                                            $outputLines[] = "TW {$tw}: <strong>{$nilai}</strong>";
                                        }
                                    }
                                    echo implode('<br>', $outputLines);
                                @endphp
                            @else
                                <span style="display: block; text-align: center;">-</span>
                            @endif
                        </td>

                        {{-- SATUAN REALISASI (DIPERBAIKI) --}}
                        <td class="text-left">
                            @if (is_array($row->satuan_realisasi) && !empty(array_filter($row->satuan_realisasi)))
                                @php
                                    $romanMap = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
                                    $outputLines = [];
                                    foreach ($row->satuan_realisasi as $triwulan => $nilai) {
                                        if ($nilai) {
                                            $tw = $romanMap[$triwulan] ?? $triwulan;
                                            $outputLines[] = "TW {$tw}: <strong>{$nilai}</strong>";
                                        }
                                    }
                                    echo implode('<br>', $outputLines);
                                @endphp
                            @else
                                <span style="display: block; text-align: center;">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="text-center">
                            @if ($row->status == 'Valid')
                                <span class="status-badge status-valid">Valid</span>
                            @else
                                <span class="status-badge status-pending">Pending</span>
                            @endif
                        </td>

                        {{-- Catatan (Pesan) --}}
                        <td class="text-center">{{ $row->pesan ?? '-' }}</td>

                        {{-- KETERANGAN / URAIAN (DIPERBAIKI) --}}
                        <td class="text-left">
                            @if (is_array($row->uraian) && !empty(array_filter($row->uraian)))
                                @php
                                    $romanMap = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
                                    $outputLines = [];
                                    foreach ($row->uraian as $triwulan => $nilai) {
                                        if ($nilai) {
                                            $tw = $romanMap[$triwulan] ?? $triwulan;
                                            $outputLines[] = "TW {$tw}: {$nilai}";
                                        }
                                    }
                                    echo implode('<br>', $outputLines);
                                @endphp
                            @else
                                <span style="display: block; text-align: center;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>Total Program:</strong> {{ count($monev) }} |
        <strong>Dicetak pada:</strong> {{ date('d F Y, H:i:s') }} WIB |
        Halaman 1
    </div>
</body>

</html>
