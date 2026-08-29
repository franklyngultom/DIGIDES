<?php

namespace App\Http\Controllers\Pembangunan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\PembangunanInventarisHasil;
use App\Models\PembangunanProyek;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InventarisHasilController extends Controller
{
    public function index(Request $request): View
    {
        $tahun = $request->get('tahun', (int) date('Y'));
        $kategori = $request->get('kategori');
        $kondisi = $request->get('kondisi');
        $statusPengelolaan = $request->get('status_pengelolaan');
        $search = $request->get('search');

        $query = PembangunanInventarisHasil::with(['proyek', 'creator'])
            ->where('tahun_anggaran', $tahun);

        if ($kategori) {
            $query->where('kategori_aset', $kategori);
        }

        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        if ($statusPengelolaan) {
            $query->where('status_pengelolaan', $statusPengelolaan);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_inventaris', 'like', "%{$search}%")
                    ->orWhere('nama_hasil_pembangunan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Statistics
        $totalAset = PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->count();
        $totalNilaiAset = (float) PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->sum('nilai_aset');
        $asetBaik = PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->where('kondisi', 'baik')->count();
        $asetPerluPerbaikan = PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count();

        $tahuns = PembangunanInventarisHasil::distinct()->orderByDesc('tahun_anggaran')->pluck('tahun_anggaran')->toArray();
        if (! in_array(date('Y'), $tahuns)) {
            $tahuns[] = (int) date('Y');
            rsort($tahuns);
        }

        return view('pembangunan.inventaris-hasil.index', compact(
            'items',
            'tahun',
            'kategori',
            'kondisi',
            'statusPengelolaan',
            'search',
            'totalAset',
            'totalNilaiAset',
            'asetBaik',
            'asetPerluPerbaikan',
            'tahuns'
        ));
    }

    public function create(Request $request): View
    {
        $selectedProyek = null;
        if ($request->filled('proyek_id')) {
            $selectedProyek = PembangunanProyek::find($request->get('proyek_id'));
        }

        $proyekList = PembangunanProyek::orderByDesc('id')->get();

        return view('pembangunan.inventaris-hasil.form', [
            'item'           => null,
            'selectedProyek' => $selectedProyek,
            'proyekList'     => $proyekList,
            'tahun'          => (int) request('tahun', date('Y')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran'          => 'required|integer|min:2000|max:2099',
            'nomor_inventaris'        => 'required|string|max:100|unique:pembangunan_inventaris_hasil,nomor_inventaris',
            'nama_hasil_pembangunan' => 'required|string|max:255',
            'pembangunan_proyek_id'   => 'nullable|exists:pembangunan_proyek,id',
            'kategori_aset'           => 'required|in:jalan_jembatan,bangunan_gedung,irigasi_sanitasi,sarana_air_bersih,sarana_olahraga,fasilitas_umum,lainnya',
            'volume'                  => 'required|string|max:150',
            'lokasi'                  => 'required|string|max:255',
            'tanggal_serah_terima'    => 'nullable|date',
            'sumber_dana'             => 'required|string|max:50',
            'nilai_aset'              => 'required|numeric|min:0',
            'kondisi'                 => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_pengelolaan'      => 'required|in:dikelola_desa,diserahkan_ke_masyarakat,dikelola_bumdes,dihibahkan',
            'penanggung_jawab'        => 'nullable|string|max:150',
            'keterangan'              => 'nullable|string',
            'foto_hasil'              => 'nullable|image|max:5120',
            'file_bast'               => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_hasil')) {
            $fotoPath = $request->file('foto_hasil')->store('pembangunan/inventaris/foto', 'public');
        }

        $bastPath = null;
        if ($request->hasFile('file_bast')) {
            $bastPath = $request->file('file_bast')->store('pembangunan/inventaris/bast', 'public');
        }

        $inventaris = PembangunanInventarisHasil::create([
            'tahun_anggaran'          => $validated['tahun_anggaran'],
            'nomor_inventaris'        => $validated['nomor_inventaris'],
            'nama_hasil_pembangunan' => $validated['nama_hasil_pembangunan'],
            'pembangunan_proyek_id'   => $validated['pembangunan_proyek_id'] ?? null,
            'kategori_aset'           => $validated['kategori_aset'],
            'volume'                  => $validated['volume'],
            'lokasi'                  => $validated['lokasi'],
            'tanggal_serah_terima'    => $validated['tanggal_serah_terima'] ?? null,
            'sumber_dana'             => $validated['sumber_dana'],
            'nilai_aset'              => $validated['nilai_aset'],
            'kondisi'                 => $validated['kondisi'],
            'status_pengelolaan'      => $validated['status_pengelolaan'],
            'penanggung_jawab'        => $validated['penanggung_jawab'] ?? null,
            'keterangan'              => $validated['keterangan'] ?? null,
            'foto_hasil_path'         => $fotoPath,
            'file_bast_path'          => $bastPath,
            'created_by'              => Auth::id(),
        ]);

        activity('pembangunan')
            ->performedOn($inventaris)
            ->causedBy(Auth::user())
            ->log("Mencatat inventaris hasil pembangunan baru: {$inventaris->nomor_inventaris} - {$inventaris->nama_hasil_pembangunan}");

        return redirect()->route('pembangunan.inventaris-hasil.index', ['tahun' => $validated['tahun_anggaran']])
            ->with('success', "Inventaris hasil pembangunan {$validated['nomor_inventaris']} berhasil dicatat.");
    }

    public function show(PembangunanInventarisHasil $inventarisHasil): View
    {
        $inventarisHasil->load(['proyek', 'creator']);

        return view('pembangunan.inventaris-hasil.show', [
            'item' => $inventarisHasil,
        ]);
    }

    public function edit(PembangunanInventarisHasil $inventarisHasil): View
    {
        $proyekList = PembangunanProyek::orderByDesc('id')->get();

        return view('pembangunan.inventaris-hasil.form', [
            'item'           => $inventarisHasil,
            'selectedProyek' => $inventarisHasil->proyek,
            'proyekList'     => $proyekList,
            'tahun'          => $inventarisHasil->tahun_anggaran,
        ]);
    }

    public function update(Request $request, PembangunanInventarisHasil $inventarisHasil): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_anggaran'          => 'required|integer|min:2000|max:2099',
            'nomor_inventaris'        => 'required|string|max:100|unique:pembangunan_inventaris_hasil,nomor_inventaris,' . $inventarisHasil->id,
            'nama_hasil_pembangunan' => 'required|string|max:255',
            'pembangunan_proyek_id'   => 'nullable|exists:pembangunan_proyek,id',
            'kategori_aset'           => 'required|in:jalan_jembatan,bangunan_gedung,irigasi_sanitasi,sarana_air_bersih,sarana_olahraga,fasilitas_umum,lainnya',
            'volume'                  => 'required|string|max:150',
            'lokasi'                  => 'required|string|max:255',
            'tanggal_serah_terima'    => 'nullable|date',
            'sumber_dana'             => 'required|string|max:50',
            'nilai_aset'              => 'required|numeric|min:0',
            'kondisi'                 => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_pengelolaan'      => 'required|in:dikelola_desa,diserahkan_ke_masyarakat,dikelola_bumdes,dihibahkan',
            'penanggung_jawab'        => 'nullable|string|max:150',
            'keterangan'              => 'nullable|string',
            'foto_hasil'              => 'nullable|image|max:5120',
            'file_bast'               => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $fotoPath = $inventarisHasil->foto_hasil_path;
        if ($request->hasFile('foto_hasil')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto_hasil')->store('pembangunan/inventaris/foto', 'public');
        }

        $bastPath = $inventarisHasil->file_bast_path;
        if ($request->hasFile('file_bast')) {
            if ($bastPath && Storage::disk('public')->exists($bastPath)) {
                Storage::disk('public')->delete($bastPath);
            }
            $bastPath = $request->file('file_bast')->store('pembangunan/inventaris/bast', 'public');
        }

        $inventarisHasil->update([
            'tahun_anggaran'          => $validated['tahun_anggaran'],
            'nomor_inventaris'        => $validated['nomor_inventaris'],
            'nama_hasil_pembangunan' => $validated['nama_hasil_pembangunan'],
            'pembangunan_proyek_id'   => $validated['pembangunan_proyek_id'] ?? null,
            'kategori_aset'           => $validated['kategori_aset'],
            'volume'                  => $validated['volume'],
            'lokasi'                  => $validated['lokasi'],
            'tanggal_serah_terima'    => $validated['tanggal_serah_terima'] ?? null,
            'sumber_dana'             => $validated['sumber_dana'],
            'nilai_aset'              => $validated['nilai_aset'],
            'kondisi'                 => $validated['kondisi'],
            'status_pengelolaan'      => $validated['status_pengelolaan'],
            'penanggung_jawab'        => $validated['penanggung_jawab'] ?? null,
            'keterangan'              => $validated['keterangan'] ?? null,
            'foto_hasil_path'         => $fotoPath,
            'file_bast_path'          => $bastPath,
        ]);

        activity('pembangunan')
            ->performedOn($inventarisHasil)
            ->causedBy(Auth::user())
            ->log("Memperbarui inventaris hasil pembangunan: {$inventarisHasil->nomor_inventaris}");

        return redirect()->route('pembangunan.inventaris-hasil.show', $inventarisHasil)
            ->with('success', "Inventaris hasil pembangunan {$inventarisHasil->nomor_inventaris} berhasil diperbarui.");
    }

    public function destroy(PembangunanInventarisHasil $inventarisHasil): RedirectResponse
    {
        $nomor = $inventarisHasil->nomor_inventaris;
        $tahun = $inventarisHasil->tahun_anggaran;

        if ($inventarisHasil->foto_hasil_path && Storage::disk('public')->exists($inventarisHasil->foto_hasil_path)) {
            Storage::disk('public')->delete($inventarisHasil->foto_hasil_path);
        }
        if ($inventarisHasil->file_bast_path && Storage::disk('public')->exists($inventarisHasil->file_bast_path)) {
            Storage::disk('public')->delete($inventarisHasil->file_bast_path);
        }

        activity('pembangunan')
            ->performedOn($inventarisHasil)
            ->causedBy(Auth::user())
            ->log("Menghapus inventaris hasil pembangunan: {$nomor}");

        $inventarisHasil->delete();

        return redirect()->route('pembangunan.inventaris-hasil.index', ['tahun' => $tahun])
            ->with('success', "Inventaris {$nomor} berhasil dihapus.");
    }

    public function exportPdf(Request $request): Response
    {
        $tahun = $request->get('tahun', (int) date('Y'));
        $kategori = $request->get('kategori');
        $kondisi = $request->get('kondisi');

        $query = PembangunanInventarisHasil::with('proyek')->where('tahun_anggaran', $tahun);

        if ($kategori) {
            $query->where('kategori_aset', $kategori);
        }
        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }

        $data = $query->orderBy('nomor_inventaris', 'asc')->get();
        $desa = DesaProfile::first();
        $totalNilai = (float) $data->sum('nilai_aset');

        $pdf = Pdf::loadView('pdf.pembangunan.buku_inventaris_hasil', compact('data', 'desa', 'tahun', 'totalNilai', 'kategori', 'kondisi'))
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream("Buku_Inventaris_Hasil_Pembangunan_{$tahun}.pdf");
    }
}
