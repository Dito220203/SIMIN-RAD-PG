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
        $searchSub = $request->input('search_sub');
        $searchProduk = $request->input('search_produk');

        // Query Subprogram
        $subprogramQuery = Subprogram::where('delete_at', '0');

        if ($searchSub) {
            $subprogramQuery->where(function ($q) use ($searchSub) {
                $q->where('program', 'like', "%{$searchSub}%")
                    ->orWhere('subprogram', 'like', "%{$searchSub}%")
                    ->orWhere('uraian', 'like', "%{$searchSub}%");
            })
                ->orWhereHas('penggunas', function ($q) use ($searchSub) {
                    $q->where('nama', 'like', "%{$searchSub}%");
                });
        }

        $subprogram = $subprogramQuery->paginate(10, ['*'], 'subprogram_page');
        $subprogram->appends($request->only('search_sub'));

        // Query Produk (FotoSubprogram)
        $produkQuery = FotoSubprogram::with(['subprogram', 'penggunas']);

        if ($searchProduk) {
            $produkQuery->where(function ($q) use ($searchProduk) {
                $q->where('judul', 'like', "%{$searchProduk}%")
                    ->orWhere('keterangan', 'like', "%{$searchProduk}%");
            })
                ->orWhereHas('subprogram', function ($q) use ($searchProduk) {
                    $q->where('subprogram', 'like', "%{$searchProduk}%")
                        ->orWhere('uraian', 'like', "%{$searchProduk}%");
                })
                ->orWhereHas('penggunas', function ($q) use ($searchProduk) {
                    $q->where('nama', 'like', "%{$searchProduk}%");
                });
        }

        $produk = $produkQuery->paginate(10, ['*'], 'produk_page');
        $produk->appends($request->only('search_produk'));

        return view('admin.Subprogram.index', compact('subprogram', 'produk', 'searchSub', 'searchProduk'));
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
