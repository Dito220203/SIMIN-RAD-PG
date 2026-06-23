<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Monev;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
/**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('components.navbar', function ($view) {

            $user = Auth::guard('pengguna')->user();

            $statusData = null;
            $tanggalDeadline = null;

            if ($user && $user->id_opd) {

                // 1. Cari data dengan deadline terbaru untuk tahu acuan waktu batasnya
                $latestMonev = Monev::where('id_opd', $user->id_opd)
                    ->whereNotNull('edit_open_until')
                    ->orderBy('edit_open_until', 'desc')
                    ->first();

                if ($latestMonev && $latestMonev->edit_open_until) {

                    $tanggalDeadline = Carbon::parse($latestMonev->edit_open_until)
                        ->translatedFormat('d M Y \p\u\k\u\l H:i');

                    // ⏰ CEK DEADLINE: Apakah waktu saat ini sudah melewati batas?
                    if (now()->gt($latestMonev->edit_open_until)) {
                        $statusData = 'terlewat';
                    } else {

                        // 2. 🔥 AMBIL SEMUA DATA YANG MEMILIKI DEADLINE YANG SAMA PERSIS
                        // Jika reminder manual per-OPD, semua program akan terpanggil.
                        // Jika reminder per-data, hanya 1 program spesifik itu saja yang terpanggil.
                        $allActiveMonevs = Monev::where('id_opd', $user->id_opd)
                            ->where('edit_open_until', $latestMonev->edit_open_until)
                            ->get();

                        $isAllLengkap = true; // Asumsi awal semua data lengkap

                        // 3. Looping untuk mengecek kelengkapan seluruh program yang aktif
                        foreach ($allActiveMonevs as $monevItem) {

                            $dokumenAnggaran = $monevItem->dokumen_anggaran ?? [];
                            $realisasi       = $monevItem->realisasi ?? [];
                            $volumeTarget    = $monevItem->volumeTarget ?? [];
                            $satuanRealisasi = $monevItem->satuan_realisasi ?? [];
                            $uraian          = $monevItem->uraian ?? [];

                            // Ambil target triwulan yang dititipkan dari Controller
                            $twTarget = $dokumenAnggaran['target_tw'] ?? null;

                            // Cek kelengkapan data utama/dasar
                            $isDataDasarLengkap = !empty($monevItem->anggaran)
                                && !empty($monevItem->sumberdana)
                                && !empty($monevItem->id_opd);

                            $isTriwulanLengkap = true;

                            // CEK KELENGKAPAN BERDASARKAN TARGET TRIWULAN PER BARIS DATA
                            if ($twTarget === 'all') {
                                // Jika menagih semua triwulan, pastikan TW 1 sampai 4 terisi semua
                                for ($i = 1; $i <= 4; $i++) {
                                    if (empty($dokumenAnggaran[$i]) || empty($realisasi[$i]) || empty($volumeTarget[$i]) || empty($satuanRealisasi[$i]) || empty($uraian[$i])) {
                                        $isTriwulanLengkap = false;
                                        break;
                                    }
                                }
                            } elseif ($twTarget) {
                                // Jika menagih triwulan tertentu saja (misal: TW 2)
                                if (empty($dokumenAnggaran[$twTarget]) || empty($realisasi[$twTarget]) || empty($volumeTarget[$twTarget]) || empty($satuanRealisasi[$twTarget]) || empty($uraian[$twTarget])) {
                                    $isTriwulanLengkap = false;
                                }
                            } else {
                                // Fallback jika 'target_tw' kosong
                                $isTriwulanLengkap = !empty($dokumenAnggaran)
                                    && !empty($realisasi)
                                    && !empty($volumeTarget)
                                    && !empty($satuanRealisasi)
                                    && !empty($uraian);
                            }

                            $isBarisIniLengkap = $isDataDasarLengkap && $isTriwulanLengkap;

                            // 💥 JIKA ADA 1 SAJA PROGRAM YANG BELUM LENGKAP, LANGSUNG SET FALSE & STOP LOOP
                            if (!$isBarisIniLengkap) {
                                $isAllLengkap = false;
                                break;
                            }
                        }

                        // Hasil status ditentukan dari akumulasi pengecekan semua program di atas
                        $statusData = $isAllLengkap ? 'lengkap' : 'belum';
                    }
                }
            }

            $view->with([
                'statusData' => $statusData,
                'tanggalDeadline' => $tanggalDeadline
            ]);
        });
    }
}
