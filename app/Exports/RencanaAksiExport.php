<?php

namespace App\Exports;

use App\Models\RencanaAksi_6_tahun;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RencanaAksiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithCustomStartCell, WithColumnWidths
{
    protected $tahun; // ✅ 1. Tambahkan property untuk menampung tahun

    // ✅ 2. Buat constructor untuk menerima variabel tahun dari controller
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mulai query seperti biasa
        $query = RencanaAksi_6_tahun::with(['subprogram', 'opd', 'penggunas'])
            ->where('delete_at', '0');

        // ✅ 3. Terapkan filter where jika variabel tahun tidak kosong
        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        // Ambil data setelah query selesai
        $rencanaAksis = $query->get();

        $rows = collect();
        $no = 1;

        foreach ($rencanaAksis as $item) {
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
                        'Tahun'            => $item->tahun,
                        'Perangkat Daerah' => $item->opd->nama ?? '-',
                        'Anggaran'         => $anggarans[$i] ?? '-',
                        'Sumber Dana'      => $sumberdanas[$i] ?? '-',
                        'Keterangan'       => $item->keterangan,
                    ]);
                } else {
                    $rows->push([
                        'NO'               => '',
                        'Strategi'      => '',
                        'Rencana Aksi'     => '',
                        'Sub Kegiatan'     => '',
                        'Kegiatan'         => '',
                        'Nama Program'     => '',
                        'Lokasi'           => '',
                        'Volume'           => '',
                        'Satuan'           => '',
                        'Tahun'            => '',
                        'Perangkat Daerah' => '',
                        'Anggaran'         => $anggarans[$i] ?? '-',
                        'Sumber Dana'      => $sumberdanas[$i] ?? '-',
                        'Keterangan'       => '',
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
            'Tahun',
            'Perangkat Daerah',
            'Anggaran',
            'Sumber Dana',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul Utama
        $sheet->mergeCells('A1:N1'); // Sesuaikan dengan jumlah kolom
        $sheet->setCellValue('A1', 'Rencana Aksi RAD-PG');
        $sheet->getRowDimension(1)->setRowHeight(30);

        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Style untuk semua sel (border dan perataan)
        $sheet->getStyle("A3:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Style Header
        $headerRange = "A3:{$lastColumn}3";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '375534']],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(28);

        // Style Judul Utama
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // LOGIKA UNTUK MERGE SEL SECARA OTOMATIS
        $startRow = 4;
        $currentRow = $startRow;
        $collection = $this->collection();

        foreach ($collection as $index => $row) {
            if (!empty($row['NO'])) {
                $mergeCount = 0;
                for ($j = $index + 1; $j < count($collection); $j++) {
                    if (empty($collection[$j]['NO'])) {
                        $mergeCount++;
                    } else {
                        break;
                    }
                }

                if ($mergeCount > 0) {
                    $endRow = $currentRow + $mergeCount;
                    // Daftar kolom yang akan di-merge (semua kecuali Anggaran dan Sumber Dana)
                    $columnsToMerge = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'N'];
                    foreach ($columnsToMerge as $column) {
                        $sheet->mergeCells("{$column}{$currentRow}:{$column}{$endRow}");
                    }
                }
            }
            $currentRow++;
        }
    }

    public function title(): string
    {
        return 'Rencana Aksi';
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
            'J' => 10,
            'K' => 25,
            'L' => 20,
            'M' => 20,
            'N' => 30,
        ];
    }
}
