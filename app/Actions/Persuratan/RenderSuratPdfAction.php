<?php

namespace App\Actions\Persuratan;

use App\Models\DesaProfile;
use App\Models\SuratArsip;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFInstance;
use Illuminate\Support\Facades\Storage;

class RenderSuratPdfAction
{
    /**
     * Render PDF instance for given SuratArsip.
     */
    public function render(SuratArsip $suratArsip): DomPDFInstance
    {
        $desa = DesaProfile::current();
        $template = $suratArsip->template;
        $penduduk = $suratArsip->penduduk;
        $payload = $suratArsip->payload_data ?? [];

        // Check if blade template exists, fallback to default
        $bladeView = 'pdf.surat.default';
        if ($template && $template->template_blade) {
            if (view()->exists($template->template_blade)) {
                $bladeView = $template->template_blade;
            } elseif (view()->exists('pdf.surat.' . $template->template_blade)) {
                $bladeView = 'pdf.surat.' . $template->template_blade;
            }
        }

        $pdf = Pdf::loadView($bladeView, [
            'surat' => $suratArsip,
            'template' => $template,
            'penduduk' => $penduduk,
            'desa' => $desa,
            'payload' => $payload,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
        ]);

        return $pdf;
    }

    /**
     * Render and save PDF file to storage.
     */
    public function renderAndStore(SuratArsip $suratArsip): string
    {
        $pdf = $this->render($suratArsip);
        $fileName = 'surat_' . str_replace(['/', '\\', ' '], '_', $suratArsip->nomor_surat) . '_' . time() . '.pdf';
        $storagePath = "surat/arsip/{$suratArsip->id}/{$fileName}";

        Storage::disk('local')->makeDirectory("surat/arsip/{$suratArsip->id}");
        Storage::disk('local')->put($storagePath, $pdf->output());

        $suratArsip->update(['file_pdf_path' => $storagePath]);

        return $storagePath;
    }
}
