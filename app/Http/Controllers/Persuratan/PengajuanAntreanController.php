<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\SuratArsip;
use App\Models\SuratTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengajuanAntreanController extends Controller
{
    /**
     * Daftar antrian pengajuan masuk — untuk petugas yang memiliki permission persuratan.view
     */
    public function index(Request $request): View
    {
        $query = PengajuanSurat::with(['suratTemplate', 'citizenProfile', 'user'])
            ->latest();

        // Filter by status
        $statusFilter = $request->query('status', 'menunggu');
        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        // Filter by template / jenis surat
        if ($request->filled('template')) {
            $query->where('surat_template_id', $request->query('template'));
        }

        // Search by nomor or name
        if ($request->filled('q')) {
            $keyword = $request->query('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_pengajuan', 'like', "%{$keyword}%")
                  ->orWhere('pemohon_nama', 'like', "%{$keyword}%")
                  ->orWhere('pemohon_nik', 'like', "%{$keyword}%");
            });
        }

        $pengajuan  = $query->paginate(15)->withQueryString();
        $templates  = SuratTemplate::where('is_active', true)->orderBy('nama_surat')->get();

        // Stats for the header badges
        $stats = [
            'menunggu'        => PengajuanSurat::where('status', 'menunggu')->count(),
            'diproses'        => PengajuanSurat::where('status', 'diproses')->count(),
            'perlu_perbaikan' => PengajuanSurat::where('status', 'perlu_perbaikan')->count(),
            'selesai'         => PengajuanSurat::where('status', 'selesai')->count(),
        ];

        return view('persuratan.antrean.index', compact(
            'pengajuan', 'templates', 'stats', 'statusFilter'
        ));
    }

    /**
     * Tampilkan detail pengajuan untuk diproses petugas.
     */
    public function show(PengajuanSurat $pengajuan): View
    {
        $pengajuan->load(['suratTemplate', 'citizenProfile', 'user', 'logs.user', 'diprosesByUser']);

        return view('persuratan.antrean.show', compact('pengajuan'));
    }

    /**
     * Ubah status pengajuan — satu action untuk semua transisi status.
     * Payload: action = diproses | perlu_perbaikan | disetujui | selesai | ditolak
     */
    public function updateStatus(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        Gate::authorize('update', $pengajuan);

        $request->validate([
            'action'           => ['required', 'in:diproses,perlu_perbaikan,disetujui,selesai,ditolak'],
            'catatan_petugas'  => ['nullable', 'string', 'max:1000'],
            'pesan_ke_pemohon' => ['nullable', 'string', 'max:1000'],
        ], [
            'action.required' => 'Pilih tindakan yang akan dilakukan.',
            'action.in'       => 'Tindakan tidak valid.',
        ]);

        $newStatus = $request->action;

        // Validate allowed transitions
        $allowed = $this->allowedTransitions($pengajuan->status);
        if (! in_array($newStatus, $allowed)) {
            return back()->with('error', "Status tidak dapat diubah dari '{$pengajuan->statusLabel()}' ke '{$newStatus}'.");
        }

        $previousStatus = $pengajuan->status;

        // Jika petugas memilih aksi 'selesai' dan surat belum pernah diterbitkan, otomatis terbitkan surat resmi
        if ($newStatus === 'selesai' && ! $pengajuan->surat_arsip_id) {
            /** @var \App\Actions\Persuratan\TerbitkanSuratPengajuanAction $action */
            $action = app(\App\Actions\Persuratan\TerbitkanSuratPengajuanAction::class);
            $arsip = $action->execute($pengajuan, Auth::user(), [
                'catatan_petugas'  => $request->catatan_petugas,
                'pesan_ke_pemohon' => $request->pesan_ke_pemohon,
            ]);

            return redirect()
                ->route('persuratan.antrean.show', $pengajuan)
                ->with('success', "Permohonan selesai dan surat resmi nomor {$arsip->nomor_surat} berhasil diterbitkan serta dicatat ke Buku Ekspedisi & Agenda.");
        }

        $logNote = $request->catatan_petugas;
        if ($request->filled('pesan_ke_pemohon')) {
            $logNote = $logNote ? "{$logNote} | Pesan Warga: {$request->pesan_ke_pemohon}" : "Pesan Warga: {$request->pesan_ke_pemohon}";
        }

        $pengajuan->update([
            'status'           => $newStatus,
            'catatan_petugas'  => $logNote,
            'pesan_ke_pemohon' => $request->pesan_ke_pemohon,
            'diproses_oleh'    => Auth::id(),
            'diproses_pada'    => $pengajuan->diproses_pada ?? now(),
            'selesai_pada'     => in_array($newStatus, ['selesai', 'ditolak']) ? now() : $pengajuan->selesai_pada,
        ]);

        $pengajuan->logs()->create([
            'user_id'        => Auth::id(),
            'status_sebelum' => $previousStatus,
            'status_sesudah' => $newStatus,
            'catatan'        => $request->pesan_ke_pemohon ?? $request->catatan_petugas ?? "Status diubah menjadi {$newStatus}",
        ]);

        // Kirim notifikasi aman ke akun warga pemohon
        if ($pengajuan->user) {
            $pengajuan->user->notify(new \App\Notifications\StatusPengajuanNotification($pengajuan, $request->pesan_ke_pemohon));
        }

        activity('pengajuan_antrean')
            ->causedBy(Auth::user())
            ->performedOn($pengajuan)
            ->log("Petugas " . Auth::user()->name . " mengubah status pengajuan {$pengajuan->nomor_pengajuan} dari '{$previousStatus}' menjadi '{$newStatus}'.");

        $label = $pengajuan->statusLabel();
        return redirect()
            ->route('persuratan.antrean.show', $pengajuan)
            ->with('success', "Status pengajuan {$pengajuan->nomor_pengajuan} berhasil diubah menjadi: {$label}.");
    }

    /**
     * Terbitkan surat resmi dari antrean permohonan.
     */
    public function terbitkanSurat(Request $request, PengajuanSurat $pengajuan, \App\Actions\Persuratan\TerbitkanSuratPengajuanAction $action): RedirectResponse
    {
        Gate::authorize('update', $pengajuan);

        $request->validate([
            'catatan_petugas'  => ['nullable', 'string', 'max:1000'],
            'pesan_ke_pemohon' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $arsip = $action->execute($pengajuan, Auth::user(), [
                'catatan_petugas'  => $request->catatan_petugas,
                'pesan_ke_pemohon' => $request->pesan_ke_pemohon,
            ]);

            return redirect()
                ->route('persuratan.antrean.show', $pengajuan)
                ->with('success', "Surat resmi nomor {$arsip->nomor_surat} berhasil diterbitkan dan otomatis terdaftar di Buku Ekspedisi serta Buku Agenda.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menerbitkan surat: ' . $e->getMessage());
        }
    }

    /**
     * Download dokumen surat resmi yang telah diterbitkan (PDF).
     */
    public function downloadSurat(PengajuanSurat $pengajuan, \App\Actions\Persuratan\RenderSuratPdfAction $pdfAction)
    {
        Gate::authorize('view', $pengajuan);

        $arsip = $pengajuan->suratArsip;
        if (! $arsip) {
            abort(404, 'Surat fisik/digital belum diterbitkan untuk pengajuan ini.');
        }

        if (! $arsip->file_pdf_path || ! Storage::disk('local')->exists($arsip->file_pdf_path)) {
            $pdfAction->renderAndStore($arsip);
        }

        $filename = 'Surat_' . str_replace(['/', '\\', ' '], '_', $arsip->nomor_surat) . '.pdf';

        return Storage::disk('local')->download($arsip->file_pdf_path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Download dokumen pendukung yang diunggah warga (private storage).
     */
    public function downloadDokumen(PengajuanSurat $pengajuan, int $index)
    {
        $dokumen = $pengajuan->dokumen_path_json[$index] ?? null;

        if (! $dokumen || ! Storage::disk('private')->exists($dokumen['path'])) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('private')->download($dokumen['path'], $dokumen['original_name']);
    }

    /**
     * Transitions map: which statuses can follow a given status.
     */
    private function allowedTransitions(string $current): array
    {
        return match ($current) {
            'menunggu'        => ['diproses', 'perlu_perbaikan', 'ditolak'],
            'diproses'        => ['perlu_perbaikan', 'disetujui', 'selesai', 'ditolak'],
            'perlu_perbaikan' => ['diproses', 'ditolak'],
            'disetujui'       => ['selesai', 'ditolak'],
            'selesai'         => [],       // Terminal
            'ditolak'         => [],       // Terminal
            default           => [],
        };
    }
}
