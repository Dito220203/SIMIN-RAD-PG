<?php

namespace App\Http\Controllers;

use App\Models\Regulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Hilangkan search dan pagination
        $regulasi = Regulasi::all();

        return view('admin.Regulasi.index', compact('regulasi'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.Regulasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'judul' => 'required',
            'tanggal' => 'required',
            'status' => 'required',
            'file' => 'required|mimes:pdf|max:2048'
        ]);

        // simpan file ke storage/app/public/regulasi
        $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('regulasi', $fileName, 'public');

        Regulasi::create([
            'id_pengguna' => Auth::guard('pengguna')->id(),
            'judul'       => $validate['judul'],
            'tanggal'     => $validate['tanggal'],
            'status'      => $validate['status'],
            'file'        => $fileName,
        ]);

        return redirect()->route('regulasi')->with('success', 'Data Berhasil Ditambahkan');
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
        $regulasi = Regulasi::findOrFail($id);
        return view('admin.Regulasi.edit', compact('regulasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $regulasi = Regulasi::findOrFail($id);

        // Validasi
        $request->validate([
            'e_judul' => 'required',
            'e_tanggal' => 'required',
            'e_status' => 'required',
            'e_file' => 'nullable|mimes:pdf|max:2048'

        ]);

        $data = [
            'judul' => $request->input('e_judul'),
            'tanggal' => $request->input('e_tanggal'),
            'status' => $request->input('e_status'),
        ];

        if ($request->hasFile('e_file')) {
            $fileName = time() . '_' . $request->file('e_file')->getClientOriginalName();
            $request->file('e_file')->storeAs('regulasi', $fileName, 'public');

            // hapus file lama
            if ($regulasi->file && Storage::disk('public')->exists('regulasi/' . $regulasi->file)) {
                Storage::disk('public')->delete('regulasi/' . $regulasi->file);
            }

            $data['file'] = $fileName;
        }

        $regulasi->update($data);

        return redirect()->route('regulasi')->with('success', 'Data Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $regulasi = Regulasi::findOrFail($id);

        // hapus file dari storage
        if ($regulasi->file && Storage::disk('public')->exists('regulasi/' . $regulasi->file)) {
            Storage::disk('public')->delete('regulasi/' . $regulasi->file);
        }

        $regulasi->delete();

        return redirect()->route('regulasi')->with('success', 'Data Berhasil Dihapus');
    }
}
