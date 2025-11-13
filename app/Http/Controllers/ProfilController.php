<?php

namespace App\Http\Controllers;

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

        // Kirim data user ke view
        return view('admin.profil.index', compact('user', 'profil'));
    }

 public function updateProfile(Request $request)
{
    // Ambil user login dari guard 'pengguna'
    $user = Auth::guard('pengguna')->user();

    // Validasi input
    $request->validate([
        'email' => 'nullable|email',
        'telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
        'about' => 'nullable|string|max:500',
    ]);


    $profil = Profil::where('id_pengguna', $user->id)->first();

    // Jika belum ada profil, buat dulu
    if (!$profil) {
        $profil = new Profil();
        $profil->id_pengguna = $user->id;
    }

    // Update kolom di tabel profils
    $profil->email   = $request->email;
    $profil->telepon = $request->telepon;
    $profil->alamat  = $request->alamat;
    $profil->about   = $request->about;
    $profil->save();

    return back()->with('success', 'Profil berhasil diperbarui');
}


}
