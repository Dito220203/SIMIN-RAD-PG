<?php

namespace App\Exports;

use App\Models\RencanaKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RencanaExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithCustomStartCell, WithColumnWidths
{
    protected $user;
    protected $tahun; // ✅ 1. Tambahkan property untuk tahun

    // ✅ 2. Modifikasi constructor untuk menerima tahun
    public function __construct($user, $tahun)
    {
        $this->user = $user;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = RencanaKerja::with(['subprogram', 'opd', 'pengguna'])->where('delete_at', '0');

        if ($this->user->level !== 'Super Admin') {
            $query->where('id_pengguna', $this->user->id);
        }

        // ✅ 3. Tambahkan kondisi untuk memfilter berdasarkan tahun
        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        $rencanaKerjas = $query->get();

        // ... sisa kode method collection() Anda tidak perlu diubah ...
        $rows = collect();
        $no = 1;

        foreach ($rencanaKerjas as $item) {
            $anggarans = $item->anggaran ? explode('; ', $item->anggaran) : ['-'];
            $sumberdanas = $item->sumberdana ? explode('; ', $item->sumberdana) : ['-'];
            $maxRows = max(count($anggarans), count($sumberdanas));

            for ($i = 0; $i < $maxRows; $i++) {
                if ($i === 0) {
                    $rows->push([
                        'NO'               => $no,
                        'Strategi'      => $item->subprogram->subprogram ?? '-',
                        'Rencana Aksi'     => $item->rencana_aksi,
                        'Sub Kegiatan'     => $item->sub_kegiatan,
                        'Kegiatan'         => $item->kegiatan,
                        'Nama Program'     => $item->nama_program,
                        'Lokasi'           => $item->lokasi,
                        'Volume'           => $item->volume,
                        'Satuan'           => $item->satuan,
                        'Anggaran'         => $anggarans[$i] ?? '-',
                        'Sumber Dana'      => $sumberdanas[$i] ?? '-',
                        'Tahun'            => $item->tahun,
                        'Perangkat Daerah' => $item->opd->nama ?? '-',
                        'Status'           => $item->status,
                        'Keterangan'       => $item->keterangan,
                    ]);
                } else {
                    $rows->push([
                        'NO' => '',
                        'Strategi' => '',
                        'Rencana Aksi' => '',
                        'Sub Kegiatan' => '',
                        'Kegiatan' => '',
                        'Nama Program' => '',
                        'Lokasi' => '',
                        'Volume' => '',
                        'Satuan' => '',
                        'Anggaran'         => $anggarans[$i] ?? '-',
                        'Sumber Dana'      => $sumberdanas[$i] ?? '-',
                        'Tahun' => '',
                        'Perangkat Daerah' => '',
                        'Status' => '',
                        'Keterangan' => '',
                    ]);
                }
            }
            $no++;
        }

        return $rows;
    }

    // ... sisa method lain di class ini (headings, styles, dll) tidak perlu diubah ...
    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'NO',
            'Strategi',
            'Rencana Aksi',
            'Sub Kegiatan',
            'Kegiatan',
            'Nama Program',
            'Lokasi',
            'Volume',
            'Satuan',
            'Anggaran',
            'Sumber Dana',
            'Tahun',
            'Perangkat Daerah',
            'Status',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'Rencana Kegiatan RAD-PG');
        $sheet->getRowDimension(1)->setRowHeight(30);

        $collection = $this->collection();
        $rowCount = count($collection);
        $lastRow = $rowCount > 0 ? (3 + $rowCount) : 3;
        $lastColumn = 'O';

        $sheet->getStyle("A3:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $headerRange = "A3:{$lastColumn}3";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '375534']],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(28);

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        if ($rowCount > 0) {
            // =============================================================
            // PERBAIKAN: Menggunakan sintaks range yang benar "A4:A{$lastRow}"
            // =============================================================
            $leftAlignedColumns = ['B', 'C', 'D', 'E', 'F'];
            foreach ($leftAlignedColumns as $column) {
                $sheet->getStyle("{$column}4:{$column}{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }
            $centerAlignedColumns = ['A', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
            foreach ($centerAlignedColumns as $column) {
                $sheet->getStyle("{$column}4:{$column}{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            // LOGIKA MERGE SEL
            $currentRow = 4;
            foreach ($collection as $index => $row) {
                if (!empty($row['NO'])) {
                    $mergeCount = 0;
                    for ($j = $index + 1; $j < $rowCount; $j++) {
                        if (empty($collection[$j]['NO'])) $mergeCount++;
                        else break;
                    }
                    if ($mergeCount > 0) {
                        $endRow = $currentRow + $mergeCount;
                        $columnsToMerge = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'L', 'M', 'N', 'O'];
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
        return 'Rencana Kerja';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 30,
            'D' => 30,
            'E' => 25,
            'F' => 30,
            'G' => 20,
            'H' => 10,
            'I' => 10,
            'J' => 20,
            'K' => 20,
            'L' => 10,
            'M' => 25,
            'N' => 15,
            'O' => 30,
        ];
    }
}
