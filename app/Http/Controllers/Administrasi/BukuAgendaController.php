<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuAgenda;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuAgendaController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $jenis  = $request->query('jenis');
        $search = $request->query('search');

        $data = BukuAgenda::with(['createdBy', 'suratArsip'])
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%")
                  ->orWhere('asal_tujuan', 'like', "%$search%");
            }))
            ->latest('tanggal_surat')
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuAgenda::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.buku-agenda.index', compact('data', 'tahuns', 'tahun', 'jenis', 'search'));
    }

    public function create()
    {
        return view('administrasi.buku-agenda.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'                    => 'required|in:masuk,keluar',
            'nomor_urut'               => 'required|integer|min:1',
            'tahun'                    => 'required|integer|min:2000|max:2099',
            'nomor_surat'              => 'required|string|max:100',
            'tanggal_surat'            => 'required|date',
            'tanggal_diterima_dikirim' => 'required|date',
            'asal_tujuan'              => 'required|string|max:200',
            'perihal'                  => 'required|string|max:255',
            'keterangan'               => 'nullable|string',
            'file_surat'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_surat')) {
            $validated['file_pdf_path'] = $request->file('file_surat')
                ->store('buku-agenda', 'public');
            $validated['file_surat_path'] = $validated['file_pdf_path'];
        }

        BukuAgenda::create($validated);

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Agenda surat berhasil ditambahkan.');
    }

    public function edit(BukuAgenda $bukuAgenda)
    {
        return view('administrasi.buku-agenda.form', ['record' => $bukuAgenda]);
    }

    public function update(Request $request, BukuAgenda $bukuAgenda)
    {
        $validated = $request->validate([
            'jenis'                    => 'required|in:masuk,keluar',
            'nomor_urut'               => 'required|integer|min:1',
            'tahun'                    => 'required|integer|min:2000|max:2099',
            'nomor_surat'              => 'required|string|max:100',
            'tanggal_surat'            => 'required|date',
            'tanggal_diterima_dikirim' => 'required|date',
            'asal_tujuan'              => 'required|string|max:200',
            'perihal'                  => 'required|string|max:255',
            'keterangan'               => 'nullable|string',
            'file_surat'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file_surat')) {
            if ($bukuAgenda->file_surat_path) {
                Storage::disk('public')->delete($bukuAgenda->file_surat_path);
            }
            $validated['file_surat_path'] = $request->file('file_surat')
                ->store('buku-agenda', 'public');
        }

        $bukuAgenda->update($validated);

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Data agenda surat berhasil diperbarui.');
    }

    public function destroy(BukuAgenda $bukuAgenda)
    {
        if ($bukuAgenda->file_surat_path) {
            Storage::disk('public')->delete($bukuAgenda->file_surat_path);
        }
        $bukuAgenda->delete();

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Data agenda surat berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $jenis = $request->query('jenis');
        $desa = DesaProfile::current();

        $data = BukuAgenda::with(['createdBy', 'suratArsip'])
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->orderBy('tanggal_surat', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_buku_agenda', compact('data', 'desa', 'tahun', 'jenis'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Agenda_' . ($jenis ?: 'Semua_Jenis') . '_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
