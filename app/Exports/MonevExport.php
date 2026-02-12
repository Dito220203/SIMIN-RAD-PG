<?php

namespace App\Exports;

use App\Models\Monev;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonevExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithCustomStartCell, WithColumnWidths
{
    protected $tahun;
    protected $user;

    public function __construct($user, $tahun)
    {
        $this->user = $user;
        $this->tahun = $tahun;
    }

    /**
     * Mengambil dan memformat data untuk koleksi Excel.
     * Logika telah diperbaiki untuk menangani semua kolom array.
     */
    public function collection()
    {
        // Query untuk mengambil data
        $query = Monev::with(['subprogram', 'opd', 'rencanakerja']);

        if ($this->user->level !== 'Super Admin') {
            $query->where('id_pengguna', $this->user->id);
        }

        if ($this->tahun) {
            $query->whereHas('rencanakerja', function ($q) {
                $q->where('tahun', $this->tahun);
            });
        }

        $monevs = $query->get();

        // Menyusun baris-baris Excel
        $rows = collect();
        $no = 1;
        $romanMap = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];

        foreach ($monevs as $item) {
            // 1. Pecah string anggaran dan sumberdana menjadi array
            $anggarans = $item->anggaran ? explode('; ', $item->anggaran) : ['-'];
            $sumberdanas = $item->sumberdana ? explode('; ', $item->sumberdana) : ['-'];
            $maxRows = max(count($anggarans), count($sumberdanas));

            // --- FUNGSI HELPER UNTUK KONVERSI ARRAY KE STRING ---
            $arrayToString = function ($arrayData) use ($romanMap) {
                if (!is_array($arrayData) || empty(array_filter($arrayData))) {
                    return '-';
                }
                $lines = [];
                foreach ($arrayData as $tw => $val) {
                    if ($val) {
                        $lines[] = "TW " . ($romanMap[$tw] ?? $tw) . ": " . $val;
                    }
                }
                return empty($lines) ? '-' : implode("\n", $lines);
            };

            // 2. Proses semua kolom array menjadi string
            $dokAnggaranStr = is_array($item->dokumen_anggaran) ? implode("\n", array_filter($item->dokumen_anggaran)) : '';
            if (empty($dokAnggaranStr)) $dokAnggaranStr = '-';

            $realisasiStr       = $arrayToString($item->realisasi);
            $volTargetStr       = $arrayToString($item->volumeTarget);
            $satuanRealisasiStr = $arrayToString($item->satuan_realisasi); // DIPERBAIKI: Menambahkan kolom yang hilang
            $uraianStr          = $arrayToString($item->uraian);          // DIPERBAIKI: Mengatasi error

            // 3. Looping untuk membuat baris Excel
            for ($i = 0; $i < $maxRows; $i++) {
                if ($i === 0) {
                    // Baris pertama berisi semua data
                    $rows->push([
                        'No'                => $no,
                        'Strategi'       => $item->subprogram->subprogram ?? '-',
                        'Rencana Aksi'      => $item->rencanakerja->rencana_aksi ?? '-',
                        'Sub Kegiatan'      => $item->rencanakerja->sub_kegiatan ?? '-',
                        'Kegiatan'          => $item->rencanakerja->kegiatan ?? '-',
                        'Program'           => $item->rencanakerja->nama_program ?? '-',
                        'Lokasi'            => $item->rencanakerja->lokasi ?? '-',
                        'Vol Target'        => $item->rencanakerja->volume ?? '-',
                        'Satuan'            => $item->rencanakerja->satuan ?? '-',
                        'Anggaran'          => $anggarans[$i] ?? '-',
                        'Sumber Dana'       => $sumberdanas[$i] ?? '-',
                        'Tahun'             => $item->rencanakerja->tahun ?? '-',
                        'Perangkat Daerah'  => $item->opd->nama ?? '-',
                        'Dokumen Anggaran'  => $dokAnggaranStr,
                        'Realisasi Anggaran'         => $realisasiStr,
                        'Vol Realisasi'     => $volTargetStr,
                        'Satuan Volume'  => $satuanRealisasiStr, // DIPERBAIKI: Menambahkan kolom baru
                        'Status'            => $item->status,
                        'Catatan'           => $item->pesan ?? '-',
                        'Ket'               => $uraianStr, // DIPERBAIKI: Menggunakan string yang sudah diproses
                    ]);
                } else {
                    // Baris selanjutnya hanya berisi anggaran dan sumber dana
                    $rows->push([
                        'No' => '', 'Strategi' => '', 'Rencana Aksi' => '', 'Sub Kegiatan' => '',
                        'Kegiatan' => '', 'Program' => '', 'Lokasi' => '', 'Vol Target' => '', 'Satuan' => '',
                        'Anggaran'          => $anggarans[$i] ?? '-',
                        'Sumber Dana'       => $sumberdanas[$i] ?? '-',
                        'Tahun' => '', 'Perangkat Daerah' => '', 'Dokumen Anggaran' => '', 'Realisasi Anggaran' => '',
                        'Vol Realisasi' => '', 'Satuan Volume' => '', 'Status' => '', 'Catatan' => '', 'Ket' => '',
                    ]);
                }
            }
            $no++;
        }

        return $rows;
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        // DIPERBAIKI: Menambahkan 'Satuan Realisasi' dan menyesuaikan urutan
        return [
            'No', 'Strategi', 'Rencana Aksi', 'Sub Kegiatan', 'Kegiatan', 'Program',
            'Lokasi', 'Vol Target', 'Satuan', 'Anggaran', 'Sumber Dana', 'Tahun',
            'Perangkat Daerah', 'Dokumen Anggaran', 'Realisasi Anggaran', 'Vol Realisasi',
            'Satuan Volume', 'Status', 'Catatan', 'Ket',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'T'; // DIPERBAIKI: Diubah dari S ke T karena ada kolom baru
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'Monitoring dan Evaluasi RAD-PG');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        $headerRange = "A3:{$lastColumn}3";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '375534']],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(28);

        $collection = $this->collection();
        $rowCount = $collection->count();
        $lastRow = $rowCount > 0 ? (3 + $rowCount) : 3;

        $sheet->getStyle("A3:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        if ($rowCount > 0) {
            $leftAlignedColumns = ['B', 'C', 'D', 'E', 'F', 'O', 'P', 'Q', 'T']; // DIPERBAIKI: Kolom array rata kiri
            foreach ($leftAlignedColumns as $column) {
                $sheet->getStyle("{$column}4:{$column}{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
            $centerAlignedColumns = ['A', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'R', 'S']; // DIPERBAIKI
            foreach ($centerAlignedColumns as $column) {
                $sheet->getStyle("{$column}4:{$column}{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            // Logika untuk menggabungkan sel (MERGE CELL)
            $currentRow = 4;
            foreach ($collection as $index => $row) {
                if (!empty($row['No'])) {
                    $mergeCount = 0;
                    for ($j = $index + 1; $j < $rowCount; $j++) {
                        if (empty($collection[$j]['No'])) $mergeCount++;
                        else break;
                    }
                    if ($mergeCount > 0) {
                        $endRow = $currentRow + $mergeCount;
                        // DIPERBAIKI: Menyesuaikan kolom yang akan di-merge
                        $columnsToMerge = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
                        foreach ($columnsToMerge as $column) {
                            $sheet->mergeCells("{$column}{$currentRow}:{$column}{$endRow}");
                        }
                    }
                }
                $currentRow++;
            }
        }
    }

    public function title(): string
    {
        return 'Monitoring dan Evaluasi';
    }

    public function columnWidths(): array
    {
        // DIPERBAIKI: Menambahkan lebar untuk kolom 'Q' (Satuan Realisasi) dan menyesuaikan kolom lainnya
        return [
            'A' => 5,  'B' => 25, 'C' => 30, 'D' => 30, 'E' => 25,
            'F' => 30, 'G' => 20, 'H' => 10, 'I' => 10, 'J' => 20,
            'K' => 20, 'L' => 10, 'M' => 25, 'N' => 20, 'O' => 20,
            'P' => 20, 'Q' => 20, 'R' => 15, 'S' => 30, 'T' => 30,
        ];
    }
}
