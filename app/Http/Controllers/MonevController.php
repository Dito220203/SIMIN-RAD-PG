<?php

namespace App\Http\Controllers;


use App\Models\FotoProgres;
use App\Models\Map;
use App\Models\Monev;
use App\Models\Notifikasi;
use App\Models\Opd;
use App\Models\Pengguna;
use App\Models\Pesan;
use App\Models\ProgresKerja;
use App\Models\RencanaKerja;
use App\Models\Subprogram;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MonevController extends Controller
{
    public function index(Request $request)
    {
        Monev::whereNotNull('edit_open_until')
            ->where('edit_open_until', '<', Carbon::now())
            ->update([
                'is_locked' => 1,
            ]);
        $user = Auth::guard('pengguna')->user();
        $query = Monev::query();

        // ✅ Load relasi (TETAP)
        $query->with(['opd', 'subprogram', 'fotoProgres', 'map']);

        // ✅ Ambil daftar tahun (TETAP)
        $tahuns = RencanaKerja::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // ✅ Batasi berdasarkan user (kecuali Super Admin) (TETAP)
        if ($user->level !== 'Super Admin') {
            $query->where('id_pengguna', $user->id);
        }

        // ✅ Filter Tahun (TETAP)
        if ($request->filled('tahun')) {
            $query->whereHas('rencanakerja', function ($q) use ($request) {
                $q->where('tahun', $request->tahun);
            });
        }

        // DIHAPUS: Seluruh blok logika Filter Search if ($request->filled('search')) { ... }
        // Blok ini telah dihapus.

        // ...

        // MENGHAPUS PAGINATION: Mengganti paginate(10) dengan get()
        // Mengganti $query->oldest()->paginate(10)->appends($request->query());
        // Menjadi:
        $monev = $query->oldest()->get(); // Ambil semua data tanpa pagination

        $opdIdsWithData = Monev::select('id_opd')->whereNotNull('id_opd')->distinct()->pluck('id_opd');

        $allOpds = Opd::whereIn('id', $opdIdsWithData)->orderBy('nama', 'asc')->get();

        // Variabel yang dikirimkan ke view tidak berubah (karena 'search' tidak ada di compact sebelumnya)
        return view('admin.MonitoringEvaluasi.index', compact('monev', 'tahuns', 'allOpds'));
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard('pengguna')->user();

        $subprogram = Subprogram::where('delete_at', '0')->get();

        if ($user->level === 'Super Admin') {
            $rencana = RencanaKerja::where('delete_at', '0')->get();
        } else {
            $rencana = RencanaKerja::where('id_pengguna', $user->id)
                ->where('delete_at', '0')
                ->get();
        }

        $opd = Opd::where('delete_at', '0')->get();

        return view('admin.MonitoringEvaluasi.create', compact('subprogram', 'rencana', 'opd'));
    }

    // Ambil daftar rencana kerja berdasarkan subprogram
    public function getRencanaKerja($id_subprogram)
    {
        $user = Auth::guard('pengguna')->user();

        if ($user->level === 'Super Admin') {
            $rencanaKerja = RencanaKerja::where('id_subprogram', $id_subprogram)
                ->where('delete_at', '0')
                ->get(['id', 'rencana_aksi']);
        } else {
            $rencanaKerja = RencanaKerja::where('id_subprogram', $id_subprogram)
                ->where('id_pengguna', $user->id)
                ->where('delete_at', '0')
                ->get(['id', 'rencana_aksi']);
        }

        return response()->json($rencanaKerja);
    }

    // Ambil detail rencana kerja
    public function getDetailRencanaKerja($id)
    {
        $rencana = RencanaKerja::where('delete_at', '0')->findOrFail($id);

        return response()->json([
            'sub_kegiatan' => $rencana->sub_kegiatan,
            'kegiatan'     => $rencana->kegiatan,
            'nama_program' => $rencana->nama_program,
            'tahun'        => $rencana->tahun,
        ]);
    }





    /**
     * Store a newly created resource in storage.
     */
    // MonevController.php

	  public function storeFoto(Request $request)
    {
        $validatedData = $request->validate([
            'monev_id'   => 'required|exists:monevs,id',
            'foto.*'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'deskripsi'  => 'nullable|string|max:255',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        // =========================
        // UPDATE / CREATE FOTO
        // =========================
        if ($request->hasFile('foto')) {

            // hapus foto lama hanya jika upload baru
            $existingFotos = FotoProgres::where('id_monev', $validatedData['monev_id'])->get();

            foreach ($existingFotos as $foto) {
                if (Storage::disk('public')->exists($foto->foto)) {
                    Storage::disk('public')->delete($foto->foto);
                }
                $foto->delete();
            }

            // simpan foto baru
            foreach ($request->file('foto') as $file) {

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension    = $file->getClientOriginalExtension();
                $safeName     = Str::slug($originalName);

                $uniqueName   = $safeName . '-' . uniqid() . '.' . $extension;

                $path = $file->storeAs('foto_progres', $uniqueName, 'public');

                FotoProgres::create([
                    'id_monev'    => $validatedData['monev_id'],
                    'id_pengguna' => Auth::guard('pengguna')->id(),
                    'foto'        => $path,
                    'deskripsi'   => $validatedData['deskripsi'] ?? null,
                ]);
            }
        }

        // =========================
        // UPDATE DESKRIPSI
        // =========================
        if (!$request->hasFile('foto')) {

            FotoProgres::where('id_monev', $validatedData['monev_id'])
                ->update([
                    'deskripsi' => $validatedData['deskripsi']
                ]);
        }

        // =========================
        // MAP UPDATE OR CREATE
        // =========================
        if ($request->filled(['latitude', 'longitude'])) {

            Map::updateOrCreate(
                [
                    'id_monev' => $validatedData['monev_id'],
                ],
                [
                    'id_pengguna' => Auth::guard('pengguna')->id(),
                    'latitude'    => $request->latitude,
                    'longitude'   => $request->longitude,
                ]
            );
        }

        return redirect()->route('monev')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function updatePesan(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'nullable|string',
        ]);

        $monev = Monev::findOrFail($id);
        $monev->pesan = $request->pesan;
        $monev->save();

        return redirect()->route('monev')->with('success', 'Pesan berhasil disimpan');
    }


    public function validasi(string $id)
    {
        $monev = Monev::findOrFail($id);
        $monev->status = 'Valid';
        $monev->save();

        return redirect()->route('monev')->with('success', 'Status berhasil divalidasi');
    }

    public function updateStatus(string $id)
    {
        $monev = Monev::findOrFail($id);

        // ganti status progres
        if ($monev->status === 'Valid') {
            $monev->status = 'Belum divalidasi';
        } else {
            $monev->status = 'Valid';
        }
        $monev->save();

        return redirect()->route('monev')->with('success', 'Status berhasil diperbarui');
    }

    /**
     * Display the specified resource.
     */
    // ... (method-method lain yang sudah ada)

    public function exportExcel(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::guard('pengguna')->user();

        // Ambil filter tahun dari URL
        $tahun = $request->input('tahun');

        // Tentukan nama file
        $fileName = 'laporan_monev.xlsx';
        if ($tahun) {
            $fileName = 'laporan_monev_' . $tahun . '.xlsx';
        }

        // Panggil class Export dengan parameter user dan tahun
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MonevExport($user, $tahun), $fileName);
    }

    // ... (sisa method di controller)

    public function exportPDF(Request $request)
    {
        // Ambil nilai tahun dari request
        $selectedTahun = $request->input('tahun');

        // Ambil user yang sedang login
        $user = Auth::guard('pengguna')->user();

        // Siapkan query dasar dengan relasi yang dibutuhkan
        $query = Monev::with(['subprogram', 'opd', 'rencanakerja']);

        // Filter data jika bukan Super Admin
        if ($user->level !== 'Super Admin') {
            $query->where('id_pengguna', $user->id);
        }

        // =============================================================
        // BAGIAN YANG DIPERBAIKI: Filter tahun
        // =============================================================
        if ($selectedTahun) {
            // Mencari 'tahun' melalui relasi 'rencanakerja'
            $query->whereHas('rencanakerja', function ($q) use ($selectedTahun) {
                $q->where('tahun', $selectedTahun);
            });
        }
        // =============================================================

        // Ambil semua data hasil query
        $monev = $query->orderBy('created_at', 'desc')->get();

        // Siapkan data untuk dikirim ke view PDF
        $data = [
            'monev' => $monev,
            'tahun' => $selectedTahun, // Kirim variabel tahun ke view
        ];

        // Buat dan atur PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.MonitoringEvaluasi.export',
            $data
        )->setPaper('a4', 'landscape');

        // Beri nama file yang dinamis dan download
        $fileName = 'laporan_monev' . ($selectedTahun ? '_' . $selectedTahun : '') . '.pdf';
        return $pdf->download($fileName);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::guard('pengguna')->user();
        $monev = Monev::findOrFail($id);

        if ($user->level === 'Super Admin') {
            // Subprogram dari semua rencana kerja
            $subprogram = Subprogram::whereIn('id', RencanaKerja::pluck('id_subprogram'))->get();
            $rencana = RencanaKerja::all();
        } else {
            // Subprogram hanya dari rencana kerja user
            $subprogram = Subprogram::whereIn(
                'id',
                RencanaKerja::where('id_pengguna', $user->id)->pluck('id_subprogram')
            )->get();

            $rencana = RencanaKerja::where('id_pengguna', $user->id)->get();
        }

        $opd = Opd::where('delete_at', '0')->get();
        $monev->anggaran = explode('; ', $monev->anggaran);
        $monev->sumberdana = explode('; ', $monev->sumberdana);

        return view('admin.MonitoringEvaluasi.update', compact('monev', 'subprogram', 'rencana', 'opd'));
    }


    public function update(Request $request, string $id)
    {
        // 1. Temukan data yang akan diupdate
        $monev = Monev::findOrFail($id);

        // 2. Validasi semua input dari request
        $validatedData = $request->validate([
            'id_subprogram' => 'required|exists:subprograms,id',
            'id_opd'        => 'required|exists:opds,id',


            'anggaran'     => 'required|array',
            'anggaran.*'   => 'required|string',
            'sumberdana'   => 'required|array',
            'sumberdana.*' => 'required|string',
            // Validasi untuk data triwulan sebagai array
            'dokumen_anggaran'  => 'nullable|array',
            'realisasi'     => 'nullable|array',
            'volumeTarget'  => 'nullable|array',
            'satuan_realisasi'  => 'nullable|array',
            'uraian'    => 'nullable|array',
        ]);

        $anggaranString = implode('; ', $validatedData['anggaran']);
        $sumberdanaString = implode('; ', $validatedData['sumberdana']);
// Ambil data lama untuk menyelamatkan 'target_tw'
        $dokumenLama = $monev->dokumen_anggaran ?? [];
        $dokumenBaru = $validatedData['dokumen_anggaran'] ?? [];

        // Jika admin pernah menitipkan 'target_tw', pindahkan ke data yang baru disubmit
        if (isset($dokumenLama['target_tw'])) {
            $dokumenBaru['target_tw'] = $dokumenLama['target_tw'];
        }

        // 3. Siapkan data untuk diupdate dengan memetakan nama field
        $updateData = [
            'id_subprogram'    => $validatedData['id_subprogram'],
            'id_opd'           => $validatedData['id_opd'],
            'anggaran'         => $anggaranString,
            'sumberdana'       => $sumberdanaString,

            // Gunakan $dokumenBaru yang sudah kita amankan flag-nya
            'dokumen_anggaran' => $dokumenBaru,

            'realisasi'        => $validatedData['realisasi'] ?? [],
            'volumeTarget'     => $validatedData['volumeTarget'] ?? [],
            'satuan_realisasi' => $validatedData['satuan_realisasi'] ?? [],
            'uraian'           => $validatedData['uraian'] ?? [],
        ];
        // 4. Lakukan update pada data
        $monev->update($updateData);


        return redirect()->route('monev')->with('success', 'Data Monitoring Evaluasi berhasil diperbarui.');
    }

   public function bulkToggleLock(Request $request)
    {
        // 1. Pastikan hanya Super Admin yang bisa mengakses
        if (auth()->guard('pengguna')->user()->level !== 'Super Admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // 2. Validasi input dari form
        $validated = $request->validate([
            'opd_id' => 'required|exists:opds,id',
            'action' => 'required|in:lock,unlock',
        ], [
            'opd_id.required' => 'Anda harus memilih Perangkat Daerah.',
            'action.required' => 'Anda harus memilih Aksi.',
        ]);

        $opdId = $validated['opd_id'];
        $action = $validated['action'];
        $opd = Opd::findOrFail($opdId);

        if ($action === 'lock') {
            Monev::where('id_opd', $opdId)->update([
                'is_locked' => 1,
                'edit_open_until' => null,
            ]);
        } else {
            Monev::where('id_opd', $opdId)->update([
                'is_locked' => 0,
                'edit_open_until' => null,
            ]);
        }

        $actionText = $action === 'lock' ? 'dikunci' : 'dibuka';
        $message = "Semua data untuk OPD {$opd->nama} berhasil {$actionText}.";
        return back()->with('success', $message);
    }

    public function destroy(string $id)
    {
        $monev = Monev::with('fotoProgres')->findOrFail($id);
        if ($monev->fotoProgres->isNotEmpty()) {
            foreach ($monev->fotoProgres as $foto) {
                Storage::disk('public')->delete($foto->foto);
            }
        }
        $monev->delete();
        return redirect()->route('monev')->with('success', 'Data Berhasil Dihapus');
    }

	public function reminderManual(Request $request)
    {
        try {
            $request->validate([
                'opd_id' => 'required',
                'triwulan' => 'required',
                'deadline_tanggal' => 'required|date',
                'deadline_jam' => 'required',
            ], [
                'opd_id.required' => 'Pilih OPD terlebih dahulu.',
                'triwulan.required' => 'Pilih triwulan terlebih dahulu.',
                'deadline_tanggal.required' => 'Tanggal batas wajib diisi.',
                'deadline_jam.required' => 'Jam batas wajib diisi.',
            ]);

            Carbon::setLocale('id');

            $triwulanKey = $request->triwulan;
            $batasWaktu = Carbon::parse($request->deadline_tanggal . ' ' . $request->deadline_jam);
            $formatBatas = $batasWaktu->translatedFormat('d F Y \p\u\k\u\l H:i');

            if ($request->opd_id === 'all') {
                $opds = Opd::whereNotNull('no_tlp')
                    ->where('no_tlp', '!=', '')
                    ->where('delete_at', '0')
                    ->get();
            } else {
                $opds = Opd::where('id', $request->opd_id)
                    ->whereNotNull('no_tlp')
                    ->where('no_tlp', '!=', '')
                    ->where('delete_at', '0')
                    ->get();
            }

            foreach ($opds as $opd) {
                $daftarMonev = Monev::with('rencanakerja')
                    ->where('id_opd', $opd->id)
                    ->get();

                if ($daftarMonev->isEmpty()) {
                    continue;
                }

                $tahunData = $daftarMonev->first()->rencanakerja->tahun ?? Carbon::now()->year;
                $listProgramString = "";
                $jumlahBelum = 0;

                // 🔥 PERBAIKAN: Kita lakukan update di dalam loop ini agar data tiap baris tidak saling timpa
                foreach ($daftarMonev as $index => $mv) {
                    // Amankan pembacaan JSON
                    $dokumen = is_string($mv->dokumen_anggaran) ? json_decode($mv->dokumen_anggaran, true) : ($mv->dokumen_anggaran ?? []);
                    $namaProgram = $mv->rencanakerja->nama_program ?? 'Program Tanpa Nama';
                    $no = $index + 1;

                    if ($triwulanKey === 'all') {
                        $twStatuses = [];
                        $isProgramBelum = false;

                        foreach (['1' => 'I', '2' => 'II', '3' => 'III', '4' => 'IV'] as $key => $romawi) {
                            $lengkap = !empty($dokumen[$key] ?? null);
                            $statusText = $lengkap ? '✅ SUDAH LENGKAP' : '❌ BELUM LENGKAP';

                            if (!$lengkap) {
                                $isProgramBelum = true;
                            }
                            $twStatuses[$romawi] = $statusText;
                        }

                        if ($isProgramBelum) {
                            $jumlahBelum++;
                        }

                        $listProgramString .= "\n{$no}. Program : {$namaProgram} : TW I ({$twStatuses['I']})"
                            . "\n                      TW II ({$twStatuses['II']})"
                            . "\n                      TW III ({$twStatuses['III']})"
                            . "\n                      TW IV ({$twStatuses['IV']})";
                    } else {
                        $romawi = match ($triwulanKey) {
                            '1' => 'I', '2' => 'II', '3' => 'III', '4' => 'IV', default => 'I'
                        };

                        $lengkap = !empty($dokumen[$triwulanKey] ?? null);
                        $statusText = $lengkap ? '✅ SUDAH LENGKAP' : '❌ BELUM LENGKAP';

                        if (!$lengkap) {
                            $jumlahBelum++;
                        }

                        $listProgramString .= "\n{$no}. Program : {$namaProgram} : TW {$romawi} ({$statusText})";
                    }

                    // 💾 SIMPAN TITIPAN & BUKA GEMBOK PER BARIS (Agar data lama aman)
                    $dokumen['target_tw'] = $triwulanKey;
                    $mv->update([
                        'is_locked' => 0,
                        'edit_open_until' => $batasWaktu,
                        'dokumen_anggaran' => $dokumen
                    ]);
                }

                $pesan = "📢 *Laporan Status RAD PG*\n\n"
                    . "Yth. *{$opd->nama}*,\n\n"
                    . "Berikut adalah status kelengkapan *Dokumen Anggaran Tahun {$tahunData}* pada sistem:\n"
                    . "----------------------------------"
                    . "{$listProgramString}\n"
                    . "----------------------------------\n\n";

                if ($jumlahBelum > 0) {
                    $pesan .= "⚠️ Masih terdapat *{$jumlahBelum} program* yang *BELUM* diunggah (tanda ❌).\n"
                        . "Mohon segera melengkapi data tersebut.\n\n";
                } else {
                    $pesan .= "👏 Terpantau semua program *SUDAH LENGKAP* (tanda ✅).\n"
                        . "Terima kasih atas kerjasamanya.\n\n";
                }

                $pesan .= "🕒 Akses edit data dibuka sampai *{$formatBatas}*.\n"
                    . "Silakan segera lakukan perbaikan/lengkapi data sebelum batas waktu tersebut.\n\n"
                    . "Terima kasih.\nAdmin Bapprida.";

                $this->kirimWA($opd->no_tlp, $pesan);
                sleep(3);

                // HAPUS update massal Monev::where(...) di sini karena sudah dipindah ke dalam loop di atas
            }

            $flashMessage = ($request->opd_id === 'all')
                ? 'Reminder WhatsApp berhasil dikirim ke semua OPD dan akses edit dibuka.'
                : "Reminder WhatsApp berhasil dikirim ke OPD dan akses edit dibuka.";

            return redirect()->route('monev')->with('success', $flashMessage);

        } catch (\Exception $e) {
            return redirect()->route('monev')->with('error', 'Terjadi kesalahan saat mengirim reminder: ' . $e->getMessage());
        }
    }
    public function reminderPerData(Request $request, $id)
    {
        $request->validate([
            'triwulan' => 'required',
            'deadline_tanggal' => 'required|date',
            'deadline_jam' => 'required',
        ], [
            'triwulan.required' => 'Pilih triwulan terlebih dahulu.',
            'deadline_tanggal.required' => 'Tanggal batas wajib diisi.',
            'deadline_jam.required' => 'Jam batas wajib diisi.',
        ]);

        Carbon::setLocale('id');

        $monev = Monev::with('rencanakerja', 'opd')->findOrFail($id);

        $tahunData = $monev->rencanakerja->tahun ?? Carbon::now()->year;
        $triwulanKey = $request->triwulan;

        $batasWaktu = Carbon::parse($request->deadline_tanggal . ' ' . $request->deadline_jam);
        $formatBatas = $batasWaktu->translatedFormat('d F Y \\p\\u\\k\\u\\l H:i');

        $dokumen = $monev->dokumen_anggaran ?? [];
        $namaProgram = $monev->rencanakerja->nama_program ?? 'Program Tanpa Nama';
        $opd = $monev->opd;

        $listProgramString = "";
        $jumlahBelum = 0;

        // Jika memilih Semua Triwulan
        if ($triwulanKey === 'all') {
            $twStatuses = [];
            $isProgramBelum = false;

            foreach (['1' => 'I', '2' => 'II', '3' => 'III', '4' => 'IV'] as $key => $romawi) {
                $lengkap = !empty($dokumen[$key] ?? null);
                $statusText = $lengkap ? '✅ SUDAH LENGKAP' : '❌ BELUM LENGKAP';

                if (!$lengkap) {
                    $isProgramBelum = true;
                }

                $twStatuses[$romawi] = $statusText;
            }

            if ($isProgramBelum) {
                $jumlahBelum = 1;
            }

            $listProgramString .= "\n1. Program : {$namaProgram} : TW I ({$twStatuses['I']})"
                . "\n                     TW II ({$twStatuses['II']})"
                . "\n                     TW III ({$twStatuses['III']})"
                . "\n                     TW IV ({$twStatuses['IV']})";
        } else {
            // Jika memilih salah satu Triwulan saja
            $romawi = match ($triwulanKey) {
                '1' => 'I',
                '2' => 'II',
                '3' => 'III',
                '4' => 'IV',
                default => 'I'
            };

            $lengkap = !empty($dokumen[$triwulanKey] ?? null);
            $statusText = $lengkap ? '✅ SUDAH LENGKAP' : '❌ BELUM LENGKAP';

            if (!$lengkap) {
                $jumlahBelum = 1;
            }

            $listProgramString .= "\n1. Program : {$namaProgram} : TW {$romawi} ({$statusText})";
        }

        // Susun template pesan baru
        $pesan = "📢 *Laporan Status RAD PG*\n\n"
            . "Yth. *{$opd->nama}*,\n\n"
            . "Berikut adalah status kelengkapan *Dokumen Anggaran Tahun {$tahunData}* pada sistem:\n"
            . "----------------------------------"
            . "{$listProgramString}\n"
            . "----------------------------------\n\n";

        if ($jumlahBelum > 0) {
            $pesan .= "⚠️ Masih terdapat *{$jumlahBelum} program* yang *BELUM* diunggah (tanda ❌).\n"
                . "Mohon segera melengkapi data tersebut.\n\n";
        } else {
            $pesan .= "👏 Terpantau semua program *SUDAH LENGKAP* (tanda ✅).\n"
                . "Terima kasih atas kerjasamanya.\n\n";
        }

        $pesan .= "🕒 Akses edit data dibuka sampai *{$formatBatas}*.\n"
            . "Silakan segera lakukan perbaikan/lengkapi data sebelum batas waktu tersebut.\n\n"
            . "Terima kasih.\nAdmin Bapprida.";

        $this->kirimWA($opd->no_tlp, $pesan);

        sleep(3);
		$dokumenSaatIni = $monev->dokumen_anggaran ?? [];
        // Titipkan informasi triwulan yang ditagih ke dalam array tersebut
        $dokumenSaatIni['target_tw'] = $triwulanKey;
        $monev->update([
            'is_locked' => 0,
            'edit_open_until' => $batasWaktu,
					   'dokumen_anggaran' => $dokumenSaatIni
        ]);

        return back()->with(
            'success',
            'Reminder berhasil dikirim dan akses edit dibuka!'
        );
    }

    private function kirimWA($no, $pesan)
    {
        $no = preg_replace('/^0/', '62', $no);

        $data = http_build_query([
            'target' => $no,
            'message' => $pesan,
            'countryCode' => '62',
        ]);

        $options = [
            'http' => [
                'header'  =>
                "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Authorization: " . env('FONNTE_TOKEN') . "\r\n",
                'method'  => 'POST',
                'content' => $data,
            ],
        ];

        file_get_contents('https://api.fonnte.com/send', false, stream_context_create($options));
    }
}
