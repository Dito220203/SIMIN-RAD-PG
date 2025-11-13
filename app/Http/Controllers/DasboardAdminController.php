<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use App\Models\Monev;
use App\Models\Pengguna;
use App\Models\ProgresKerja;
use App\Models\RencanaAksi_6_tahun;
use App\Models\RencanaKerja;
use Illuminate\Http\Request;

class DasboardAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->guard('pengguna')->user();

        $totalRencanaAksi = RencanaAksi_6_tahun::count();
        // ===== Bagian untuk Rencana Kerja =====
        if ($user->level == 'Super Admin') {

            $totalRencanaKerja = RencanaKerja::active()->count();
            $rencanaSelesai    = RencanaKerja::active()->where('status', 'Valid')->count();
            $rencanaProgress   = RencanaKerja::active()->where('status', 'tidak valid')->count();
        } else {
            $totalRencanaKerja = RencanaKerja::active()->where('id_pengguna', $user->id)->count();
            $rencanaSelesai    = RencanaKerja::active()->where('id_pengguna', $user->id)->where('status', 'Valid')->count();
            $rencanaProgress   = RencanaKerja::active()->where('id_pengguna', $user->id)->where('status', 'tidak valid')->count();
        }

        // ===== Bagian untuk Monev =====
        if ($user->level == 'Super Admin') {
            $allMonev = Monev::all();
        } else {
            $allMonev = Monev::where('id_pengguna', $user->id)->get();
        }



        // ===== [BARU] Bagian untuk Progres Kerja =====
        // Asumsi: ProgresKerja memiliki relasi 'id_pengguna'
        // Jika ProgresKerja tidak memiliki scope 'active()', Anda bisa gunakan ::count() saja
        if ($user->level == 'Super Admin') {
            $totalProgresKerja = ProgresKerja::count();
        } else {
            $totalProgresKerja = ProgresKerja::where('id_pengguna', $user->id)->count();
        }



        // filter data lengkap
        $monevLengkap = $allMonev->filter(function ($item) {
            $requiredFields = [
                'id_pengguna',
                'id_subprogram',
                'rencana_aksi',
                'sub_kegiatan',
                'kegiatan',
                'nama_program',
                'lokasi',
                'volume',
                'satuan',
                'anggaran',
                'sumberdana',
                'tahun',
                'id_opd',


                'dokumen_anggaran',
                'realisasi',
                'volumeTarget',
                'satuan_realisasi',
                'uraian' // pastikan 'uraian' ada di sini
            ];

            foreach ($requiredFields as $field) {
                if (empty($item->$field)) {
                    return false; // tidak lengkap
                }
            }

            return true; // lengkap
        })->count();



        $totalMonev = $allMonev->count();
        $monevBelumLengkap = $totalMonev - $monevLengkap;

        return view('admin.Dasboard.index', compact(
            'totalRencanaAksi',
            'totalProgresKerja',
            'totalRencanaKerja',
            'rencanaSelesai',
            'rencanaProgress',
            'totalMonev',
            'monevLengkap',
            'monevBelumLengkap'
        ));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function navbarNotifikasi()
    {
        $rencana = RencanaKerja::latest()->take(5)->get();
        $progres = ProgresKerja::latest()->take(5)->get();
        $monev   = Monev::latest()->take(5)->get();

        // Gabung jadi satu collection
        $notifikasi = $rencana->concat($progres)->concat($monev)
            ->sortByDesc('created_at')
            ->take(5); // ambil 5 terbaru dari gabungan

        return view('components.navbar', compact('notifikasi'));
    }
}
