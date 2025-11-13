<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\FotoSubprogram;
use App\Models\Subprogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubProgramController extends Controller
{
    public function index(Request $request)
    {
      $subprogram = Subprogram::where('delete_at', '0')->get();

    // Hapus seluruh Query Produk (FotoSubprogram)

    // Kirim hanya data subprogram ke view.
    return view('admin.Subprogram.index', compact('subprogram'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program' => 'required',
            'subprogram' => 'required',
            'uraian' => 'required',
        ]);

        // Simpan data subprogram
        Subprogram::create([
            'id_pengguna' => Auth::guard('pengguna')->id(),
            'program' => $request->program,
            'subprogram' => $request->subprogram,
            'uraian' => $request->uraian,
        ]);


        return redirect()->route('subprogram')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'id_subprogram' => 'required|exists:subprograms,id',
            'judul' => 'required',
            'keterangan' => 'nullable',
            'foto' => 'required|image|max:2048',
        ]);


        $filename = time() . '.' . $request->file('foto')->getClientOriginalExtension();
        $filePath = $request->file('foto')->storeAs('produk', $filename, 'public');


        FotoSubprogram::create([
            'id_pengguna' => Auth::guard('pengguna')->id(),
            'id_subprogram' => $request->id_subprogram,
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'foto' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Produk Subprogram berhasil ditambahkan!');
    }


    public function update(Request $request, string $id)
    {
        $validasi = Subprogram::findOrFail($id);
        $request->validate([
            'e_program' => 'required',
            'e_subprogram' => 'required',
            'e_uraian' => 'required',
        ]);

        $validasi->update([
            'program' => $request->input('e_program'),
            'subprogram' => $request->input('e_subprogram'),
            'uraian' => $request->input('e_uraian'),
        ]);

        return redirect()->route('subprogram')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Subprogram::where('id', $id)->update([
            'delete_at' => '1'
        ]);

        return redirect()->route('subprogram')->with('success', 'Data Berhasil Dihapus');
    }

    public function destroyProduk(string $id)
    {
        $produk = FotoSubprogram::findOrFail($id);

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk Subprogram berhasil dihapus!');
    }
}
