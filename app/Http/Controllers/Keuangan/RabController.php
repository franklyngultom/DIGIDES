<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\KeuanganRab;
use App\Models\KeuanganRabItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RabController extends Controller
{
    public function index(Request $request): View
    {
        $tahun = $request->get('tahun', (int) date('Y'));
        $sumberDana = $request->get('sumber_dana');
        $status = $request->get('status');
        $search = $request->get('search');

        $query = KeuanganRab::with(['items', 'creator'])
            ->where('tahun_anggaran', $tahun);

        if ($sumberDana) {
            $query->where('sumber_dana', $sumberDana);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_rab', 'like', "%{$search}%")
                    ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('bidang', 'like', "%{$search}%")
                    ->orWhere('nama_ppkd', 'like', "%{$search}%");
            });
        }

        $rabs = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Statistics
        $totalRabs = KeuanganRab::where('tahun_anggaran', $tahun)->count();
        $totalAnggaran = (float) KeuanganRab::where('tahun_anggaran', $tahun)->sum('total_anggaran');
        $totalDisetujui = KeuanganRab::where('tahun_anggaran', $tahun)->where('status', 'disetujui')->count();
        $totalDraft = KeuanganRab::where('tahun_anggaran', $tahun)->where('status', 'draft')->count();

        $tahuns = KeuanganRab::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran')->toArray();
        if (! in_array(date('Y'), $tahuns)) {
            $tahuns[] = (int) date('Y');
            rsort($tahuns);
        }

        return view('keuangan.rab.index', compact(
            'rabs',
            'tahun',
            'sumberDana',
            'status',
            'search',
            'totalRabs',
            'totalAnggaran',
            'totalDisetujui',
            'totalDraft',
            'tahuns'
        ));
    }

    public function create(): View
    {
        return view('keuangan.rab.form', [
            'rab'        => null,
            'tahun'      => (int) request('tahun', date('Y')),
            'sumberDana' => request('sumber_dana', 'DDS'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran'    => 'required|integer|min:2000|max:2099',
            'nomor_rab'         => 'required|string|max:100|unique:keuangan_rabs,nomor_rab',
            'bidang'            => 'required|string|max:255',
            'sub_bidang'        => 'nullable|string|max:255',
            'nama_kegiatan'     => 'required|string|max:255',
            'lokasi'            => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|string|max:100',
            'sumber_dana'       => 'required|string|max:50',
            'nama_ppkd'         => 'nullable|string|max:150',
            'jabatan_ppkd'      => 'nullable|string|max:150',
            'status'            => 'required|in:draft,disetujui,direalisasikan',
            'keterangan'        => 'nullable|string',
            'file_lampiran'     => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
            'items'             => 'required|array|min:1',
            'items.*.kategori'     => 'required|in:bahan_material,upah_tenaga_kerja,sewa_alat,operasional',
            'items.*.kode_rekening'=> 'nullable|string|max:50',
            'items.*.uraian'       => 'required|string|max:255',
            'items.*.volume'       => 'required|numeric|min:0.01',
            'items.*.satuan'       => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.keterangan'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $filePath = null;
            if ($request->hasFile('file_lampiran')) {
                $filePath = $request->file('file_lampiran')->store('keuangan/rab', 'public');
            }

            $rab = KeuanganRab::create([
                'tahun_anggaran'     => $validated['tahun_anggaran'],
                'nomor_rab'          => $validated['nomor_rab'],
                'bidang'             => $validated['bidang'],
                'sub_bidang'         => $validated['sub_bidang'] ?? null,
                'nama_kegiatan'      => $validated['nama_kegiatan'],
                'lokasi'             => $validated['lokasi'],
                'waktu_pelaksanaan'  => $validated['waktu_pelaksanaan'],
                'sumber_dana'        => $validated['sumber_dana'],
                'nama_ppkd'          => $validated['nama_ppkd'] ?? null,
                'jabatan_ppkd'       => $validated['jabatan_ppkd'] ?? null,
                'total_anggaran'     => 0,
                'status'             => $validated['status'],
                'keterangan'         => $validated['keterangan'] ?? null,
                'file_lampiran_path' => $filePath,
                'created_by'         => Auth::id(),
            ]);

            $totalAnggaran = 0;
            foreach ($validated['items'] as $index => $itemData) {
                $volume = (float) $itemData['volume'];
                $hargaSatuan = (float) $itemData['harga_satuan'];
                $totalHarga = $volume * $hargaSatuan;
                $totalAnggaran += $totalHarga;

                KeuanganRabItem::create([
                    'keuangan_rab_id' => $rab->id,
                    'kode_rekening'   => $itemData['kode_rekening'] ?? null,
                    'kategori'        => $itemData['kategori'],
                    'uraian'          => $itemData['uraian'],
                    'volume'          => $volume,
                    'satuan'          => $itemData['satuan'],
                    'harga_satuan'    => $hargaSatuan,
                    'total_harga'     => $totalHarga,
                    'keterangan'      => $itemData['keterangan'] ?? null,
                    'urutan'          => $index + 1,
                ]);
            }

            $rab->update(['total_anggaran' => $totalAnggaran]);

            activity('keuangan')
                ->performedOn($rab)
                ->causedBy(Auth::user())
                ->log("Membuat dokumen RAB baru: {$rab->nomor_rab} - {$rab->nama_kegiatan} sebesar Rp " . number_format($totalAnggaran, 0, ',', '.'));
        });

        return redirect()->route('keuangan.rab.index', ['tahun' => $validated['tahun_anggaran']])
            ->with('success', "Dokumen RAB {$validated['nomor_rab']} berhasil disimpan.");
    }

    public function show(KeuanganRab $rab): View
    {
        $rab->load(['items', 'creator']);

        // Group items by category
        $groupedItems = $rab->items->groupBy('kategori');

        // Category Totals
        $categoryTotals = [
            'bahan_material'    => (float) $rab->items->where('kategori', 'bahan_material')->sum('total_harga'),
            'upah_tenaga_kerja' => (float) $rab->items->where('kategori', 'upah_tenaga_kerja')->sum('total_harga'),
            'sewa_alat'         => (float) $rab->items->where('kategori', 'sewa_alat')->sum('total_harga'),
            'operasional'       => (float) $rab->items->where('kategori', 'operasional')->sum('total_harga'),
        ];

        return view('keuangan.rab.show', compact('rab', 'groupedItems', 'categoryTotals'));
    }

    public function edit(KeuanganRab $rab): View
    {
        $rab->load('items');

        return view('keuangan.rab.form', [
            'rab'        => $rab,
            'tahun'      => $rab->tahun_anggaran,
            'sumberDana' => $rab->sumber_dana,
        ]);
    }

    public function update(Request $request, KeuanganRab $rab): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran'    => 'required|integer|min:2000|max:2099',
            'nomor_rab'         => 'required|string|max:100|unique:keuangan_rabs,nomor_rab,' . $rab->id,
            'bidang'            => 'required|string|max:255',
            'sub_bidang'        => 'nullable|string|max:255',
            'nama_kegiatan'     => 'required|string|max:255',
            'lokasi'            => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|string|max:100',
            'sumber_dana'       => 'required|string|max:50',
            'nama_ppkd'         => 'nullable|string|max:150',
            'jabatan_ppkd'      => 'nullable|string|max:150',
            'status'            => 'required|in:draft,disetujui,direalisasikan',
            'keterangan'        => 'nullable|string',
            'file_lampiran'     => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
            'items'             => 'required|array|min:1',
            'items.*.kategori'     => 'required|in:bahan_material,upah_tenaga_kerja,sewa_alat,operasional',
            'items.*.kode_rekening'=> 'nullable|string|max:50',
            'items.*.uraian'       => 'required|string|max:255',
            'items.*.volume'       => 'required|numeric|min:0.01',
            'items.*.satuan'       => 'required|string|max:50',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.keterangan'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $rab, $validated) {
            $filePath = $rab->file_lampiran_path;
            if ($request->hasFile('file_lampiran')) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file_lampiran')->store('keuangan/rab', 'public');
            }

            $rab->update([
                'tahun_anggaran'     => $validated['tahun_anggaran'],
                'nomor_rab'          => $validated['nomor_rab'],
                'bidang'             => $validated['bidang'],
                'sub_bidang'         => $validated['sub_bidang'] ?? null,
                'nama_kegiatan'      => $validated['nama_kegiatan'],
                'lokasi'             => $validated['lokasi'],
                'waktu_pelaksanaan'  => $validated['waktu_pelaksanaan'],
                'sumber_dana'        => $validated['sumber_dana'],
                'nama_ppkd'          => $validated['nama_ppkd'] ?? null,
                'jabatan_ppkd'       => $validated['jabatan_ppkd'] ?? null,
                'status'             => $validated['status'],
                'keterangan'         => $validated['keterangan'] ?? null,
                'file_lampiran_path' => $filePath,
            ]);

            // Replace items cleanly
            $rab->items()->delete();

            $totalAnggaran = 0;
            foreach ($validated['items'] as $index => $itemData) {
                $volume = (float) $itemData['volume'];
                $hargaSatuan = (float) $itemData['harga_satuan'];
                $totalHarga = $volume * $hargaSatuan;
                $totalAnggaran += $totalHarga;

                KeuanganRabItem::create([
                    'keuangan_rab_id' => $rab->id,
                    'kode_rekening'   => $itemData['kode_rekening'] ?? null,
                    'kategori'        => $itemData['kategori'],
                    'uraian'          => $itemData['uraian'],
                    'volume'          => $volume,
                    'satuan'          => $itemData['satuan'],
                    'harga_satuan'    => $hargaSatuan,
                    'total_harga'     => $totalHarga,
                    'keterangan'      => $itemData['keterangan'] ?? null,
                    'urutan'          => $index + 1,
                ]);
            }

            $rab->update(['total_anggaran' => $totalAnggaran]);

            activity('keuangan')
                ->performedOn($rab)
                ->causedBy(Auth::user())
                ->log("Memperbarui dokumen RAB: {$rab->nomor_rab} - Total Rp " . number_format($totalAnggaran, 0, ',', '.'));
        });

        return redirect()->route('keuangan.rab.show', $rab)
            ->with('success', "Dokumen RAB {$rab->nomor_rab} berhasil diperbarui.");
    }

    public function destroy(KeuanganRab $rab): RedirectResponse
    {
        $nomor = $rab->nomor_rab;
        $tahun = $rab->tahun_anggaran;

        if ($rab->file_lampiran_path && Storage::disk('public')->exists($rab->file_lampiran_path)) {
            Storage::disk('public')->delete($rab->file_lampiran_path);
        }

        activity('keuangan')
            ->performedOn($rab)
            ->causedBy(Auth::user())
            ->log("Menghapus dokumen RAB: {$nomor}");

        $rab->delete();

        return redirect()->route('keuangan.rab.index', ['tahun' => $tahun])
            ->with('success', "Dokumen RAB {$nomor} berhasil dihapus.");
    }

    public function updateStatus(Request $request, KeuanganRab $rab): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,disetujui,direalisasikan',
        ]);

        $rab->update(['status' => $validated['status']]);

        activity('keuangan')
            ->performedOn($rab)
            ->causedBy(Auth::user())
            ->log("Mengubah status RAB {$rab->nomor_rab} menjadi {$validated['status']}");

        return back()->with('success', "Status RAB {$rab->nomor_rab} diperbarui menjadi: " . ucfirst($validated['status']));
    }

    public function exportPdf(KeuanganRab $rab): Response
    {
        $rab->load('items');
        $desa = DesaProfile::first();

        // Group items
        $groupedItems = $rab->items->groupBy('kategori');

        // Subtotals per category
        $subtotals = [
            'bahan_material'    => (float) $rab->items->where('kategori', 'bahan_material')->sum('total_harga'),
            'upah_tenaga_kerja' => (float) $rab->items->where('kategori', 'upah_tenaga_kerja')->sum('total_harga'),
            'sewa_alat'         => (float) $rab->items->where('kategori', 'sewa_alat')->sum('total_harga'),
            'operasional'       => (float) $rab->items->where('kategori', 'operasional')->sum('total_harga'),
        ];

        $pdf = Pdf::loadView('pdf.keuangan.dokumen_rab', compact('rab', 'desa', 'groupedItems', 'subtotals'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $safeName = str_replace(['/', '\\', ' '], '_', $rab->nomor_rab);

        return $pdf->stream("RAB_{$safeName}.pdf");
    }
}
