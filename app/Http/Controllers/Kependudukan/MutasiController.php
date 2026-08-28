<?php

namespace App\Http\Controllers\Kependudukan;

use App\Actions\Kependudukan\RecordMutasiAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kependudukan\PendudukMutasiRequest;
use App\Models\Penduduk;
use App\Models\PendudukMutasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MutasiController extends Controller
{
    /**
     * Display the Register Buku Mutasi Penduduk.
     */
    public function index(Request $request): View
    {
        $mutasis = PendudukMutasi::with(['penduduk', 'createdBy'])
            ->when($request->input('jenis_mutasi'), fn ($q, $v) => $q->where('jenis_mutasi', $v))
            ->when($request->input('bulan'), fn ($q, $v) => $q->whereMonth('tanggal_mutasi', $v))
            ->when($request->input('tahun'), fn ($q, $v) => $q->whereYear('tanggal_mutasi', $v))
            ->orderByDesc('tanggal_mutasi')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'tahun_ini' => PendudukMutasi::whereYear('tanggal_mutasi', now()->year)->count(),
            'lahir' => PendudukMutasi::where('jenis_mutasi', 'lahir')->whereYear('tanggal_mutasi', now()->year)->count(),
            'mati' => PendudukMutasi::where('jenis_mutasi', 'mati')->whereYear('tanggal_mutasi', now()->year)->count(),
            'pindah' => PendudukMutasi::whereIn('jenis_mutasi', ['pindah_masuk', 'pindah_keluar'])->whereYear('tanggal_mutasi', now()->year)->count(),
        ];

        return view('kependudukan.mutasi.index', compact('mutasis', 'stats'));
    }

    /**
     * Store a newly recorded mutation event.
     */
    public function store(PendudukMutasiRequest $request, Penduduk $penduduk, RecordMutasiAction $action): RedirectResponse
    {
        $data = $request->validated();

        // Handle optional supporting file upload
        if ($request->hasFile('berkas_pendukung')) {
            $data['berkas_pendukung_path'] = $request->file('berkas_pendukung')
                ->store("kependudukan/{$penduduk->id}/mutasi", 'local');
        }

        $mutasi = $action->handle($penduduk, $data);

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', "Peristiwa mutasi ({$mutasi->jenis_mutasi_label}) berhasil dicatat.");
    }
}
