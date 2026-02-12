<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $opd = Opd::where('delete_at', '0')->get();

        return view('admin.Opd.index', compact('opd'));
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
        // 1. Definisikan aturan validasi
        $rules = [
            'nama' => 'required|unique:opds,nama',
            'status' => 'required',
            'no_tlp' => 'string|nullable',
        ];

        // 2. Definisikan pesan custom untuk aturan tertentu
        $messages = [
            'nama.unique' => 'Nama OPD tersebut sudah ada! Silakan gunakan nama lain.',
        ];

        // 3. Jalankan validasi dengan aturan dan pesan custom
        $validate = $request->validate($rules, $messages);

        Opd::create($validate);


        return redirect()->route('opd')->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, string $id)
{
    // 1. Definisikan aturan validasi
    $rules = [
        // 'unique:opds,nama,'.$id  <-- Bagian ini kuncinya
        // Ini berarti: field 'e_nama' harus unik di tabel 'opds' pada kolom 'nama',
        // KECUALI untuk baris yang memiliki id = $id.
        'e_nama' => 'required|unique:opds,nama,' . $id,
        'e_status' => 'required',
        'no_tlp' => 'string|nullable',
    ];

    // 2. Definisikan pesan custom
    $messages = [
        'e_nama.unique' => 'Nama OPD tersebut sudah ada! Silakan gunakan nama lain.',
    ];

    // 3. Jalankan validasi
    $request->validate($rules, $messages);

    // 4. Temukan dan update data (tidak perlu findOrFail lagi karena sudah divalidasi)
    $opd = Opd::find($id);
    $opd->update([
        'nama' => $request->input('e_nama'),
        'status' => $request->input('e_status'),
        'no_tlp' => $request->input('no_tlp'),
    ]);


    return redirect()->route('opd')->with('success', 'Data Berhasil Diperbarui');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Opd::where('id', $id)->update([
            'delete_at' => '1'
        ]);

        return redirect()->route('opd')->with('success', 'Data Berhasil Dihapus');
    }
}
