<?php

namespace App\Http\Controllers\Kependudukan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kependudukan\PendudukStoreRequest;
use App\Http\Requests\Kependudukan\PendudukUpdateRequest;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendudukController extends Controller
{
    /**
     * Display a listing of residents (Buku Induk Penduduk).
     */
    public function index(Request $request): View
    {
        $query = Penduduk::query()
            ->search($request->input('search'))
            ->dusun($request->input('dusun'))
            ->rtRw($request->input('rt'), $request->input('rw'))
            ->jenisKelamin($request->input('jenis_kelamin'))
            ->status($request->input('status_penduduk'))
            ->kategoriUsia($request->input('kategori_usia'))
            ->orderBy('nama_lengkap');

        $penduduks = $query->paginate(20)->withQueryString();

        // Summary statistics
        $stats = [
            'total' => Penduduk::count(),
            'laki_laki' => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Penduduk::where('jenis_kelamin', 'P')->count(),
            'kk' => Penduduk::distinct('no_kk')->count('no_kk'),
            'pindah' => Penduduk::where('status_penduduk', 'pindah')->count(),
            'meninggal' => Penduduk::where('status_penduduk', 'meninggal')->count(),
        ];

        $dusunList = Penduduk::whereNotNull('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        return view('kependudukan.index', compact('penduduks', 'stats', 'dusunList'));
    }

    /**
     * Show the form for creating a new resident.
     */
    public function create(): View
    {
        return view('kependudukan.create');
    }

    /**
     * Store a newly created resident.
     */
    public function store(PendudukStoreRequest $request): RedirectResponse
    {
        $penduduk = Penduduk::create($request->validated());

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', "Data warga {$penduduk->nama_lengkap} berhasil didaftarkan.");
    }

    /**
     * Display the specified resident.
     */
    public function show(Penduduk $penduduk): View
    {
        $penduduk->load(['documents', 'mutasis.createdBy']);

        return view('kependudukan.show', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function edit(Penduduk $penduduk): View
    {
        return view('kependudukan.edit', compact('penduduk'));
    }

    /**
     * Update the specified resident.
     */
    public function update(PendudukUpdateRequest $request, Penduduk $penduduk): RedirectResponse
    {
        $penduduk->update($request->validated());

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', "Data warga {$penduduk->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Remove the specified resident.
     */
    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        $nama = $penduduk->nama_lengkap;
        $penduduk->delete();

        return redirect()
            ->route('kependudukan.index')
            ->with('success', "Data warga {$nama} berhasil dihapus.");
    }
}
