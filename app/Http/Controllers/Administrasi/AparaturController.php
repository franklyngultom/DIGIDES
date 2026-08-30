<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AparaturController extends Controller
{
    /**
     * Display listing of aparat desa.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Aparatur::with('penduduk')
            ->when($search, function ($q, $s) {
                $q->where('jabatan', 'like', "%{$s}%")
                    ->orWhere('nip', 'like', "%{$s}%")
                    ->orWhereHas('penduduk', fn($p) => $p->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status_aktif', $status == '1');
            })
            ->orderBy('status_aktif', 'desc')
            ->orderBy('id', 'asc');

        $aparatur = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Aparatur::count(),
            'aktif' => Aparatur::where('status_aktif', true)->count(),
            'perangkat' => Aparatur::where('status_kepegawaian', 'perangkat_desa')->count(),
            'pns_pppk' => Aparatur::whereIn('status_kepegawaian', ['pns', 'pppk'])->count(),
        ];

        return view('administrasi.aparatur.index', compact('aparatur', 'stats', 'search', 'status'));
    }

    public function create()
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.aparatur.form', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id|unique:aparatur,penduduk_id',
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string|max:100',
            'jam_masuk_standar' => 'required|string',
            'jam_pulang_standar' => 'required|string',
            'toleransi_terlambat_menit' => 'required|integer|min:0|max:120',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['qr_token'] = Str::random(32);
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        Aparatur::create($validated);

        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil ditambahkan ke buku register.');
    }

    public function edit(Aparatur $aparatur)
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.aparatur.form', compact('aparatur', 'penduduks'));
    }

    public function update(Request $request, Aparatur $aparatur)
    {
        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id|unique:aparatur,penduduk_id,' . $aparatur->id,
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string|max:100',
            'jam_masuk_standar' => 'required|string',
            'jam_pulang_standar' => 'required|string',
            'toleransi_terlambat_menit' => 'required|integer|min:0|max:120',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $aparatur->update($validated);

        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil diperbarui.');
    }

    public function destroy(Aparatur $aparatur)
    {
        $aparatur->delete();
        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil dihapus.');
    }
}
