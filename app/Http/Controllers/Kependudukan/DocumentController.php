<?php

namespace App\Http\Controllers\Kependudukan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kependudukan\PendudukDocumentUploadRequest;
use App\Models\Penduduk;
use App\Models\PendudukDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Upload a document for the given resident.
     */
    public function store(PendudukDocumentUploadRequest $request, Penduduk $penduduk): RedirectResponse
    {
        $file = $request->file('file');

        // Store securely in private disk (not publicly accessible)
        $path = $file->store("kependudukan/{$penduduk->id}/documents", 'local');

        $penduduk->documents()->create([
            'jenis_dokumen' => $request->input('jenis_dokumen'),
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', 'Berkas berhasil diunggah.');
    }

    /**
     * Stream / download a document securely (RBAC protected via route middleware).
     */
    public function stream(PendudukDocument $document): Response
    {
        abort_unless(
            Storage::disk('local')->exists($document->file_path),
            404,
            'Berkas tidak ditemukan.'
        );

        $content = Storage::disk('local')->get($document->file_path);
        $mimeType = $document->mime_type ?? 'application/octet-stream';

        return response($content, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$document->nama_file.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Delete the specified document.
     */
    public function destroy(PendudukDocument $document): RedirectResponse
    {
        $penduduk = $document->penduduk;

        // Remove from storage
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', 'Berkas berhasil dihapus.');
    }
}
