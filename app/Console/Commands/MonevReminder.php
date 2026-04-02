<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Opd;
use App\Models\Monev;

class MonevReminder extends Command
{
    protected $signature = 'monev:reminder';
    protected $description = 'Reminder WhatsApp Monev per Triwulan ke OPD (Filter Tahun Renja)';

    public function handle()
{
    // 1. Tentukan Waktu & Filter Tahun
    $now = Carbon::now();
    $bulan = $now->month;
    $tahun = $now->year; // Tahun sistem = Tahun Filter Renja

    // Tentukan Triwulan
    if ($bulan <= 3) {
        $triwulanKey = "1"; $namaTriwulan = 'I';
    } elseif ($bulan <= 6) {
        $triwulanKey = "2"; $namaTriwulan = 'II';
    } elseif ($bulan <= 9) {
        $triwulanKey = "3"; $namaTriwulan = 'III';
    } else {
        $triwulanKey = "4"; $namaTriwulan = 'IV';
    }

    $this->info("--- Laporan Status Triwulan {$namaTriwulan} Tahun {$tahun} ---");

    // 2. Ambil OPD
    $opds = Opd::whereNotNull('no_tlp')
        ->where('no_tlp', '!=', '')
        ->where('delete_at', '0')
        ->get();

    foreach ($opds as $opd) {

        // 3. Ambil data Monev (Hanya tahun ini)
        $daftarMonev = Monev::with('rencanakerja')
                            ->where('id_opd', $opd->id)
                            ->whereHas('rencanakerja', function($query) use ($tahun) {
                                $query->where('tahun', $tahun);
                            })
                            ->get();

        if ($daftarMonev->isEmpty()) {
            continue; // Skip jika tidak ada program tahun ini
        }

        // Variabel untuk menampung list text
        $listProgramString = "";
        $jumlahBelum = 0;

        // 4. Loop Semua Program untuk dibuat Daftarnya
        foreach ($daftarMonev as $index => $mv) {
            $dokumen = $mv->dokumen_anggaran ?? [];
            $namaProgram = $mv->rencanakerja->nama_program ?? 'Program Tanpa Nama';

            // Cek Status
            if (!isset($dokumen[$triwulanKey]) || empty($dokumen[$triwulanKey])) {
                // KONDISI: BELUM
                $icon = "❌";
                $ket = "BELUM";
                $jumlahBelum++; // Hitung berapa yang bolong
            } else {
                // KONDISI: SUDAH
                $icon = "✅";
                $ket = "SUDAH";
            }

            // Susun baris per program
            // Contoh: 1. Program Jalan (❌ BELUM)
            $no = $index + 1;
            $listProgramString .= "\n" . $no . ". " . $namaProgram . " (" . $icon . " " . $ket . ")";
        }

        // 5. Susun Pesan WA (Satu pesan memuat semua status)

        // Header Pesan
        $pesan = "📢 *Laporan Status RAD PG*\n\n"
               . "Yth. *{$opd->nama}*,\n\n"
               . "Berikut adalah status kelengkapan *Dokumen Anggaran Triwulan {$namaTriwulan} Tahun {$tahun}* pada sistem:\n"
               . "----------------------------------"
               . "{$listProgramString}\n" // List semua program masuk di sini
               . "----------------------------------\n\n";

        // Footer Pesan (Menyesuaikan kondisi)
        if ($jumlahBelum > 0) {
            $pesan .= "⚠️ Masih terdapat *{$jumlahBelum} program* yang *BELUM* diunggah (tanda ❌).\n"
                    . "Mohon segera melengkapi data tersebut.\n\n";
        } else {
            $pesan .= "👏 Terpantau semua program *SUDAH LENGKAP* (tanda ✅).\n"
                    . "Terima kasih atas kerjasamanya.\n\n";
        }

        $pesan .= "Terima kasih.\nAdmin Monev.";

        // 6. Kirim
        $this->info("Mengirim ke: {$opd->nama} -> (Belum: {$jumlahBelum})");
        $this->kirimWA($opd->no_tlp, $pesan);
        sleep(3);
    }

    $this->info('Semua proses selesai.');
}

    private function kirimWA($no, $pesan)
    {
        $no = preg_replace('/^0/', '62', $no);

        $data = http_build_query([
            'target' => $no,
            'message' => $pesan,
            'countryCode' => '62',
        ]);

        $options = [
            'http' => [
                'header'  =>
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Authorization: " . env('FONNTE_TOKEN') . "\r\n",
                'method'  => 'POST',
                'content' => $data,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        try {
            $context = stream_context_create($options);
            file_get_contents('https://api.fonnte.com/send', false, $context);
        } catch (\Exception $e) {
            $this->error("Gagal kirim: " . $e->getMessage());
        }
    }
}
