<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Monev;
use App\Models\ProgresKerja;
use App\Models\Regulasi;
use App\Models\RencanaAksi_6_tahun;
use App\Models\RencanaKerja;
use App\Models\Subprogram;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rencanaAksi = RencanaAksi_6_tahun::count();
        $rencanaKerja = RencanaKerja::where('status', 'valid')
            ->where('delete_at', '0')
            ->count();
        $Monev = Monev::where('status', 'valid')->count();
        $totalProgresCount = ProgresKerja::where('status', 'valid')->count();  // <-- TOTAL count
        $banners = Banner::where('status', 'Aktif')->get();

        // Ambil tahunList
        $tahunList = ProgresKerja::where('status', 'valid')
            ->whereHas('monev.rencanakerja')
            ->get()
            ->pluck('monev.rencanakerja.tahun')
            ->unique()
            ->sortDesc()
            ->values();


        $dataByYear = [];

        foreach ($tahunList as $tahun) {

            $bulanData = array_fill(1, 12, 0);

            // Ambil progres per tahun
            $progresData = ProgresKerja::with('monev.rencanakerja')
                ->whereHas('monev.rencanakerja', function ($q) use ($tahun) {
                    $q->where('tahun', $tahun);
                })
                ->get();

            foreach ($progresData as $p) {
                $bulan = intval($p->created_at->format('n')); // 1-12
                $bulanData[$bulan]++;
            }

            $dataByYear[$tahun] = array_values($bulanData);
        }

        return view('client.dasbor', compact(
            'rencanaAksi',
            'rencanaKerja',
            'Monev',
            'totalProgresCount',
            'banners',
            'tahunList',
            'dataByYear'
        ));
    }


    public function strategi()
    {
        $strategi = Subprogram::where('delete_at', '0')
            ->get();
        return view('client.strategi', compact('strategi'));
    }

    public function rencanaAksi()
    {
        $rencanaAksi = RencanaAksi_6_tahun::where('delete_at', '0')->get();
        return view('client.rencanaAksi', compact('rencanaAksi'));
    }


    // Di dalam ClientController.php
    public function rencanaKerja()
    {
        $rencanaKerja = RencanaKerja::where('status', 'valid')
            ->where('delete_at', '0')
            ->get();

        return view('client.rencanaKerja', compact('rencanaKerja'));
    }

    public function Monev()
    {
        $Monev = Monev::where('status', 'valid')->get();
        return view('client.monev', compact('Monev'));
    }


    public function progres()
    {
        $progres = ProgresKerja::where('status', 'valid')->get();
        return view('client.progres', compact('progres'));
    }
    public function regulasi()
    {
        $regulasi = Regulasi::where('status', 'aktif')->get();
        return view('client.regulasi', compact('regulasi'));
    }
}
