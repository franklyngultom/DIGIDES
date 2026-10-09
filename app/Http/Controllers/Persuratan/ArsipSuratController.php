<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use App\Models\SuratArsip;
use Illuminate\Http\Request;

class ArsipSuratController extends Controller
{
    /**
     * Display a list of archived surat.
     */
    public function index(Request $request)
    {
        $query = SuratArsip::with(['template', 'penduduk', 'user', 'pengajuan']);

        if ($request->filled('template_id')) {
            $query->where('surat_template_id', $request->input('template_id'));
        }
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_terbit', '>=', $request->input('tanggal_awal'));
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_terbit', '<=', $request->input('tanggal_akhir'));
        }

        $arsips = $query->orderByDesc('tanggal_terbit')->paginate(15);

        return view('persuratan.arsip_surat', compact('arsips'));
    }

    /**
     * Download or stream the letter PDF from archives.
     */
    public function download(SuratArsip $suratArsip, \App\Actions\Persuratan\RenderSuratPdfAction $pdfAction)
    {
        if (! $suratArsip->file_pdf_path || ! \Illuminate\Support\Facades\Storage::disk('local')->exists($suratArsip->file_pdf_path)) {
            $pdfAction->renderAndStore($suratArsip);
        }

        $filename = 'Arsip_' . str_replace(['/', '\\', ' '], '_', $suratArsip->nomor_surat) . '.pdf';

        return \Illuminate\Support\Facades\Storage::disk('local')->download($suratArsip->file_pdf_path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
