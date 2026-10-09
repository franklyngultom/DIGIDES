<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use App\Http\Requests\PelayananSuratRequest;
use App\Actions\Persuratan\GenerateNomorSuratAction;
use App\Actions\Persuratan\RenderSuratPdfAction;
use App\Events\SuratDiterbitkanEvent;
use App\Models\SuratTemplate;
use App\Models\SuratArsip;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelayananSuratController extends Controller
{
    /**
     * Show the form for creating a new surat (walk‑in service).
     */
    public function create()
    {
        $templates = SuratTemplate::where('is_active', true)->get();
        return view('persuratan.pelayanan_surat', compact('templates'));
    }

    /**
     * Live search for penduduk by NIK or name (AJAX).
     */
    public function searchPenduduk(Request $request)
    {
        $term = $request->query('term');
        $results = Penduduk::where('nik', 'like', "%{$term}%")
            ->orWhere('nama_lengkap', 'like', "%{$term}%")
            ->take(10)
            ->get(['id', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat_lengkap', 'status_penduduk']);
        return response()->json($results);
    }

    /**
     * Store a newly created surat.
     */
    public function store(PelayananSuratRequest $request, GenerateNomorSuratAction $nomorAction, RenderSuratPdfAction $pdfAction)
    {
        // Resolve template
        $template = SuratTemplate::findOrFail($request->input('template_id'));

        // Generate nomor surat
        $nomor = $nomorAction->execute($template);

        // Load penduduk data
        $penduduk = Penduduk::findOrFail($request->input('penduduk_id'));

        // Snapshot data + custom fields
        $payload = array_merge(
            $penduduk->only([
                'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat_lengkap', 'status_penduduk'
            ]),
            $request->except(['_token', 'template_id', 'penduduk_id'])
        );

        // Archive record
        $arsip = SuratArsip::create([
            'nomor_surat' => $nomor,
            'surat_template_id' => $template->id,
            'penduduk_id' => $penduduk->id,
            'user_id' => $request->user()->id,
            'keperluan' => $request->input('keperluan'),
            'payload_data' => $payload,
            'tanggal_terbit' => now(),
            'status' => 'terbit',
        ]);

        // Render PDF and store
        $pdfPath = $pdfAction->renderAndStore($arsip);

        // Dispatch event for sync
        SuratDiterbitkanEvent::dispatch($arsip);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Surat berhasil diterbitkan',
                'pdf_url' => Storage::url($pdfPath),
                'arsip_id' => $arsip->id,
            ]);
        }

        return redirect()->route('persuratan.arsip.index')
            ->with('success', "Surat nomor {$nomor} berhasil diterbitkan.");
    }
}
