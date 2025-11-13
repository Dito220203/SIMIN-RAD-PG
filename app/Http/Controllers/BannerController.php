<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $banner = Banner::get();

        return view('admin.banner.index', compact('banner'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'status' => 'required',
            'file'  => 'required',
        ]);

        $filePath = $request->file('file')->store('banners', 'public');

        Banner::create([
            'judul'       => $validated['judul'],
            'status'      => $validated['status'],
            'file'        => $filePath,
            'id_pengguna' => Auth::guard('pengguna')->id(),
        ]);


        return redirect()->route('banner')->with('success', 'Data Berhasil Ditambahkan');
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
        $bannerEdit = Banner::findOrFail($id);
        $banner = Banner::paginate(10);
        return view('admin.banner.index', compact('banner', 'bannerEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'e_judul' => 'required',
            'e_status' => 'required',
            'e_file'   => 'nullable',
        ]);

        $data = [
            'judul' => $request->e_judul,
            'status' => $request->e_status,
        ];

        if ($request->hasFile('e_file')) {
            if ($banner->file && Storage::disk('public')->exists($banner->file)) {
                Storage::disk('public')->delete($banner->file);
            }
            $data['file'] = $request->file('e_file')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banner')->with('success', 'Data Berhasil Diupdate');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->file && Storage::disk('public')->exists($banner->file)) {
            Storage::disk('public')->delete($banner->file);
        }
        $banner->delete();

        return redirect()->route('banner')->with('success', 'Data Berhasil Dihapus');
    }
}
