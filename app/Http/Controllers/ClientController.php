<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Monev;
use App\Models\ProgresKerja;
use App\Models\RencanaAksi_6_tahun;
use App\Models\RencanaKerja;
use App\Models\Subprogram;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rencanaAksi = RencanaAksi_6_tahun::count();
        $rencanaKerja = RencanaKerja::where('status', 'valid')
            ->where('delete_at', '0')
            ->count();

        $Monev = Monev::where('status', 'valid')->count();
        $progres = ProgresKerja::where('status', 'valid')->count();
        $banners = Banner::where('status', 'Aktif')->get();
        return view('client.dasbor', compact('rencanaAksi', 'rencanaKerja', 'Monev', 'progres', 'banners'));
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
}
