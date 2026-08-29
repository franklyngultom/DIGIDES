<?php

namespace App\Http\Controllers\Pembangunan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\PembangunanKader;
use App\Models\Penduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KaderPemberdayaanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->query('jenis');
        $search = $request->query('search');

        $query = PembangunanKader::with('penduduk')
            ->when($jenis, fn($q) => $q->where('jenis_kader', $jenis))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('jabatan', 'like', "%$search%")
                  ->orWhere('nomor_sk', 'like', "%$search%")
                  ->orWhereHas('penduduk', fn($sq) => $sq->where('nama_lengkap', 'like', "%$search%")->orWhere('nik', 'like', "%$search%"));
            }))
            ->orderBy('id', 'desc');

        $data = $query->paginate(15)->withQueryString();

        $totalKader = PembangunanKader::count();
        $totalAktif = PembangunanKader::where('status_aktif', true)->count();
        $totalHonor = PembangunanKader::where('status_aktif', true)->sum('honor_bulanan');

        return view('pembangunan.kader.index', compact('data', 'jenis', 'search', 'totalKader', 'totalAktif', 'totalHonor'));
    }

    public function create()
    {
        $penduduks = Penduduk::where('status_penduduk', 'tetap')->orderBy('nama_lengkap', 'asc')->get();
        return view('pembangunan.kader.form', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penduduk_id'   => 'required|exists:penduduks,id',
            'jenis_kader'   => 'required|in:posyandu,kpm_stunting,pendamping_desa,guru_paud,pkk,lainnya',
            'jabatan'       => 'required|string|max:100',
            'nomor_sk'      => 'nullable|string|max:100',
            'tanggal_sk'    => 'nullable|date',
            'honor_bulanan' => 'required|numeric|min:0',
            'keterangan'    => 'nullable|string',
            'status_aktif'  => 'required|boolean',
        ]);

        PembangunanKader::create($validated);

        return redirect()->route('pembangunan.kader.index')
            ->with('success', 'Data kader pemberdayaan masyarakat berhasil ditambahkan.');
    }

    public function edit(PembangunanKader $kader)
    {
        $penduduks = Penduduk::where('status_penduduk', 'tetap')->orderBy('nama_lengkap', 'asc')->get();
        return view('pembangunan.kader.form', ['record' => $kader, 'penduduks' => $penduduks]);
    }

    public function update(Request $request, PembangunanKader $kader)
    {
        $validated = $request->validate([
            'penduduk_id'   => 'required|exists:penduduks,id',
            'jenis_kader'   => 'required|in:posyandu,kpm_stunting,pendamping_desa,guru_paud,pkk,lainnya',
            'jabatan'       => 'required|string|max:100',
            'nomor_sk'      => 'nullable|string|max:100',
            'tanggal_sk'    => 'nullable|date',
            'honor_bulanan' => 'required|numeric|min:0',
            'keterangan'    => 'nullable|string',
            'status_aktif'  => 'required|boolean',
        ]);

        $kader->update($validated);

        return redirect()->route('pembangunan.kader.index')
            ->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(PembangunanKader $kader)
    {
        $kader->delete();

        return redirect()->route('pembangunan.kader.index')
            ->with('success', 'Data kader berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->query('jenis');
        $desa = DesaProfile::current();

        $data = PembangunanKader::with('penduduk')
            ->when($jenis, fn($q) => $q->where('jenis_kader', $jenis))
            ->orderBy('status_aktif', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.pembangunan.rekap_kader_desa', compact('data', 'desa', 'jenis'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Kader_Desa_' . ($jenis ?: 'Semua_Kader') . '.pdf');
    }
}
