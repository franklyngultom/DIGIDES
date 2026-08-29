<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\KeuanganKasTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuKasController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'umum');
        $kategori = in_array($type, ['bank', 'saldo']) ? 'bank' : 'tunai';
        $pembantu = $request->query('pembantu');
        $tahun = $request->query('tahun', date('Y'));
        $search = $request->query('search');

        $query = KeuanganKasTransaksi::with('creator')
            ->where(function ($q) use ($kategori, $type) {
                $q->where('kategori_kas', $kategori)
                  ->orWhere('buku_kas_type', $type);
            })
            ->when($pembantu, fn($q) => $q->where('jenis_pembantu', $pembantu))
            ->when($tahun, fn($q) => $q->where('tahun_anggaran', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', "%$search%")
                  ->orWhere('uraian', 'like', "%$search%")
                  ->orWhere('kode_rekening', 'like', "%$search%");
            }))
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc');

        $data = $query->paginate(15)->withQueryString();

        $tahuns = KeuanganKasTransaksi::selectRaw('DISTINCT tahun_anggaran as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if ($tahuns->isEmpty()) {
            $tahuns = collect([date('Y')]);
        }

        $totalPenerimaan = (clone $query)->sum('penerimaan');
        $totalPengeluaran = (clone $query)->sum('pengeluaran');
        $saldoKas = $totalPenerimaan - $totalPengeluaran;

        return view('keuangan.kas.index', compact(
            'data',
            'type',
            'kategori',
            'pembantu',
            'tahun',
            'tahuns',
            'search',
            'totalPenerimaan',
            'totalPengeluaran',
            'saldoKas'
        ));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'umum');
        $kategori = in_array($type, ['bank', 'saldo']) ? 'bank' : 'tunai';
        $pembantu = $request->query('pembantu');

        return view('keuangan.kas.form', compact('type', 'kategori', 'pembantu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_kas'   => 'required|in:tunai,bank,saldo',
            'jenis_pembantu' => 'nullable|in:umum,pajak,panjar',
            'buku_kas_type'  => 'nullable|in:umum,bank,kegiatan,pajak',
            'tahun_anggaran' => 'required|integer|min:2000|max:2099',
            'tanggal'        => 'required|date',
            'nomor_bukti'    => 'required|string|max:100',
            'kode_rekening'  => 'nullable|string|max:30',
            'uraian'         => 'required|string',
            'penerimaan'     => 'required|numeric|min:0',
            'pengeluaran'    => 'required|numeric|min:0',
            'sumber_dana'    => 'required|string|max:30',
            'file_bukti'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Normalize kategori_kas
        $kategori = in_array($validated['kategori_kas'], ['bank', 'saldo']) ? 'bank' : 'tunai';
        $validated['kategori_kas'] = $kategori;

        // Auto determine buku_kas_type
        if (empty($validated['buku_kas_type'])) {
            if ($kategori === 'bank') {
                $validated['buku_kas_type'] = 'bank';
            } elseif ($validated['jenis_pembantu'] === 'pajak') {
                $validated['buku_kas_type'] = 'pajak';
            } elseif ($validated['jenis_pembantu'] === 'panjar') {
                $validated['buku_kas_type'] = 'kegiatan';
            } else {
                $validated['buku_kas_type'] = 'umum';
            }
        }

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_bukti')) {
            $validated['file_bukti_path'] = $request->file('file_bukti')
                ->store('bukti-kas', 'public');
        }

        KeuanganKasTransaksi::create($validated);

        // Recalculate running balance
        KeuanganKasTransaksi::recalculateBalances($validated['tahun_anggaran'], $kategori);

        $redirectType = $kategori === 'bank' ? 'bank' : 'umum';

        return redirect()->route('keuangan.kas.index', ['type' => $redirectType, 'tahun' => $validated['tahun_anggaran']])
            ->with('success', 'Transaksi kas berhasil dicatat.');
    }

    public function edit(KeuanganKasTransaksi $ka)
    {
        $kategori = $ka->kategori_kas ?? ($ka->buku_kas_type === 'bank' ? 'bank' : 'tunai');
        $type = $ka->buku_kas_type ?? ($kategori === 'bank' ? 'bank' : 'umum');
        $pembantu = $ka->jenis_pembantu;

        return view('keuangan.kas.form', [
            'record'   => $ka,
            'type'     => $type,
            'kategori' => $kategori,
            'pembantu' => $pembantu,
        ]);
    }

    public function update(Request $request, KeuanganKasTransaksi $ka)
    {
        $validated = $request->validate([
            'kategori_kas'   => 'required|in:tunai,bank,saldo',
            'jenis_pembantu' => 'nullable|in:umum,pajak,panjar',
            'buku_kas_type'  => 'nullable|in:umum,bank,kegiatan,pajak',
            'tahun_anggaran' => 'required|integer|min:2000|max:2099',
            'tanggal'        => 'required|date',
            'nomor_bukti'    => 'required|string|max:100',
            'kode_rekening'  => 'nullable|string|max:30',
            'uraian'         => 'required|string',
            'penerimaan'     => 'required|numeric|min:0',
            'pengeluaran'    => 'required|numeric|min:0',
            'sumber_dana'    => 'required|string|max:30',
            'file_bukti'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $oldKategori = $ka->kategori_kas ?? ($ka->buku_kas_type === 'bank' ? 'bank' : 'tunai');
        $kategori = in_array($validated['kategori_kas'], ['bank', 'saldo']) ? 'bank' : 'tunai';
        $validated['kategori_kas'] = $kategori;

        if (empty($validated['buku_kas_type'])) {
            if ($kategori === 'bank') {
                $validated['buku_kas_type'] = 'bank';
            } elseif ($validated['jenis_pembantu'] === 'pajak') {
                $validated['buku_kas_type'] = 'pajak';
            } elseif ($validated['jenis_pembantu'] === 'panjar') {
                $validated['buku_kas_type'] = 'kegiatan';
            } else {
                $validated['buku_kas_type'] = 'umum';
            }
        }

        if ($request->hasFile('file_bukti')) {
            if ($ka->file_bukti_path) {
                Storage::disk('public')->delete($ka->file_bukti_path);
            }
            $validated['file_bukti_path'] = $request->file('file_bukti')
                ->store('bukti-kas', 'public');
        }

        $ka->update($validated);

        // Recalculate running balance
        KeuanganKasTransaksi::recalculateBalances($validated['tahun_anggaran'], $kategori);
        if ($oldKategori !== $kategori) {
            KeuanganKasTransaksi::recalculateBalances($validated['tahun_anggaran'], $oldKategori);
        }

        $redirectType = $kategori === 'bank' ? 'bank' : 'umum';

        return redirect()->route('keuangan.kas.index', ['type' => $redirectType, 'tahun' => $validated['tahun_anggaran']])
            ->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    public function destroy(KeuanganKasTransaksi $ka)
    {
        $kategori = $ka->kategori_kas ?? ($ka->buku_kas_type === 'bank' ? 'bank' : 'tunai');
        $type = $ka->buku_kas_type ?? ($kategori === 'bank' ? 'bank' : 'umum');
        $tahun = $ka->tahun_anggaran;

        if ($ka->file_bukti_path) {
            Storage::disk('public')->delete($ka->file_bukti_path);
        }

        $ka->delete();

        // Recalculate running balance
        KeuanganKasTransaksi::recalculateBalances($tahun, $kategori);

        return redirect()->route('keuangan.kas.index', ['type' => $type, 'tahun' => $tahun])
            ->with('success', 'Transaksi kas berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $type = $request->query('type', 'umum');
        $kategori = in_array($type, ['bank', 'saldo']) ? 'bank' : 'tunai';
        $pembantu = $request->query('pembantu');
        $tahun = $request->query('tahun', date('Y'));
        $desa = DesaProfile::current();

        $data = KeuanganKasTransaksi::with('creator')
            ->where(function ($q) use ($kategori, $type) {
                $q->where('kategori_kas', $kategori)
                  ->orWhere('buku_kas_type', $type);
            })
            ->when($pembantu, fn($q) => $q->where('jenis_pembantu', $pembantu))
            ->where('tahun_anggaran', $tahun)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.keuangan.buku_kas_umum', compact('data', 'desa', 'tahun', 'type', 'kategori', 'pembantu'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Kas_' . ucfirst($type) . '_' . $tahun . '.pdf');
    }
}
