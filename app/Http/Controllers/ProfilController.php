<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Profil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('pengguna')->user();
        $profil = Profil::where('id_pengguna', $user->id)->first();
        $opd = Opd::find($user->id_opd);

        // Kirim data user ke view
        return view('admin.profil.index', compact('user', 'profil', 'opd'));
    }

public function updateProfile(Request $request)
{
    // 1. Ambil user login
    $user = Auth::guard('pengguna')->user();

    $request->validate([
        'email'   => 'nullable|email',
        'telepon' => 'nullable|string|max:20',
        'alamat'  => 'nullable|string',
        'about'   => 'nullable|string|max:500',
    ]);
    Profil::updateOrCreate(
        ['id_pengguna' => $user->id],
        [
            'email'  => $request->email,
            'alamat' => $request->alamat,
            'about'  => $request->about,
        ]
    );

    if ($user->id_opd) {
        \App\Models\Opd::where('id', $user->id_opd)->update([
            'no_tlp' => $request->telepon
        ]);
    }

    return back()->with('success', 'Profil dan nomor telepon OPD berhasil diperbarui');
}
}
